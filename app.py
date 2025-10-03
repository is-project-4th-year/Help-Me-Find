from flask import Flask, request, render_template, send_from_directory
import tensorflow as tf
import keras
from keras.preprocessing import image
import numpy as np
import os
from PIL import Image
from collections import Counter
from rembg import remove   # for background removal

app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Load your trained MobileNetV2 model
model = keras.models.load_model('mobilenet_item_classifier.h5')

# Update with your dataset’s classes
class_names = ['keyboard', 'keys', 'laptop', 'mouse', 'phone', 'usb', 'headphones']
IMG_SIZE = (224, 224)


def get_dominant_color(filepath):
    """Remove background and extract the dominant color of the item."""
    input_img = Image.open(filepath).convert("RGBA")

    # Remove background
    result = remove(input_img)

    # Convert to RGB (ignore alpha channel)
    result = result.convert("RGB")

    # Resize for faster color analysis
    small_img = result.resize((50, 50))

    # Get all colors from the image
    pixels = list(small_img.getdata())

    # Filter out near-white or near-black pixels (background remnants)
    filtered_pixels = [
        p for p in pixels if not (
            (p[0] > 240 and p[1] > 240 and p[2] > 240) or
            (p[0] < 15 and p[1] < 15 and p[2] < 15)
        )
    ]

    if not filtered_pixels:
        return "Unknown"

    # Find the most common color
    most_common = Counter(filtered_pixels).most_common(1)[0][0]

    # Map RGB to simple color name
    r, g, b = most_common
    if r > 150 and g < 100 and b < 100:
        return "Red"
    elif g > 150 and r < 100 and b < 100:
        return "Green"
    elif b > 150 and r < 100 and g < 100:
        return "Blue"
    elif r > 200 and g > 200 and b < 100:
        return "Yellow"
    elif r > 200 and g > 200 and b > 200:
        return "White"
    elif r < 50 and g < 50 and b < 50:
        return "Black"
    elif r > 150 and g > 100 and b < 100:
        return "Orange"
    elif r > 150 and b > 150 and g < 100:
        return "Pink"
    else:
        return f"RGB({r},{g},{b})"


@app.route('/', methods=['GET', 'POST'])
def upload_file():
    item_prediction = ''
    color_prediction = ''
    uploaded_image_url = ''

    if request.method == 'POST':
        if 'file' not in request.files:
            item_prediction = 'No file part'
            return render_template('index.html',
                                   item_prediction=item_prediction,
                                   color_prediction=color_prediction,
                                   image_url=uploaded_image_url)

        file = request.files['file']
        if file.filename == '':
            item_prediction = 'No selected file'
            return render_template('index.html',
                                   item_prediction=item_prediction,
                                   color_prediction=color_prediction,
                                   image_url=uploaded_image_url)

        filepath = os.path.join(app.config['UPLOAD_FOLDER'], file.filename)
        file.save(filepath)
        uploaded_image_url = f'/uploads/{file.filename}'

        # Load and preprocess image for classification
        img = image.load_img(filepath, target_size=IMG_SIZE)
        img_array = image.img_to_array(img)
        img_array = tf.expand_dims(img_array, 0)

        # Predict item
        predictions = model.predict(img_array)
        score = tf.nn.softmax(predictions[0])
        predicted_class = class_names[np.argmax(score)]
        confidence = 100 * np.max(score)
        item_prediction = f"{predicted_class} ({confidence:.2f}%)"

        # Predict color (after background removal)
        color_prediction = get_dominant_color(filepath)

    return render_template('index.html',
                           item_prediction=item_prediction,
                           color_prediction=color_prediction,
                           image_url=uploaded_image_url)


@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)


if __name__ == '__main__':
    os.makedirs('uploads', exist_ok=True)
    app.run(debug=True)
