from flask import Flask, request, render_template, send_from_directory
import tensorflow as tf
import keras
from keras.preprocessing import image
import numpy as np
import os
from collections import Counter
from PIL import Image
from rembg import remove  # NEW: for background removal
import io

app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Load trained model
model = keras.models.load_model('mobilenet_item_classifier.h5')

# Class names
class_names = ['keyboard', 'keys', 'laptop', 'mouse', 'phone', 'usb', 'headphones']
IMG_SIZE = (224, 224)

# ---------- COLOR DETECTION HELPERS ---------- #
def get_dominant_item_color(image_path, k=3):
    """Remove background, then find dominant color of the item."""
    # Open image
    with open(image_path, "rb") as f:
        input_image = f.read()
    
    # Remove background
    output = remove(input_image)  
    img_no_bg = Image.open(io.BytesIO(output)).convert("RGBA")
    
    # Convert to RGB with transparent background turned white
    img_rgb = Image.new("RGB", img_no_bg.size, (255, 255, 255))
    img_rgb.paste(img_no_bg, mask=img_no_bg.split()[3])  # use alpha channel as mask

    img_rgb = img_rgb.resize((200, 200))  # resize for speed
    pixels = list(img_rgb.getdata())

    # Remove pure white pixels (background leftovers)
    filtered_pixels = [p for p in pixels if not (p[0] > 240 and p[1] > 240 and p[2] > 240)]

    if not filtered_pixels:  # fallback if empty
        filtered_pixels = pixels

    color_counts = Counter(filtered_pixels)
    dominant_color = color_counts.most_common(k)[0][0]
    return dominant_color


def rgb_to_color_name(rgb):
    """Convert RGB to simple color name."""
    r, g, b = rgb
    if r > 200 and g < 80 and b < 80:
        return "Red"
    elif g > 200 and r < 80 and b < 80:
        return "Green"
    elif b > 200 and r < 80 and g < 80:
        return "Blue"
    elif r > 200 and g > 200 and b > 200:
        return "White"
    elif r < 50 and g < 50 and b < 50:
        return "Black"
    elif r > 180 and g > 180 and b < 100:
        return "Yellow"
    elif r > 180 and g > 180 and b > 180:
        return "Gray/Silver"
    else:
        return f"RGB{rgb}"

# ---------- FLASK ROUTES ---------- #
@app.route('/', methods=['GET', 'POST'])
def upload_file():
    prediction = ''
    uploaded_image_url = ''

    if request.method == 'POST':
        if 'file' not in request.files:
            prediction = 'No file part'
            return render_template('index.html', prediction=prediction)

        file = request.files['file']
        if file.filename == '':
            prediction = 'No selected file'
            return render_template('index.html', prediction=prediction)

        filepath = os.path.join(app.config['UPLOAD_FOLDER'], file.filename)
        file.save(filepath)
        uploaded_image_url = f'/uploads/{file.filename}'

        # -------- ITEM PREDICTION -------- #
        img = image.load_img(filepath, target_size=IMG_SIZE)
        img_array = image.img_to_array(img)
        img_array = tf.expand_dims(img_array, 0)

        predictions = model.predict(img_array)
        score = tf.nn.softmax(predictions[0])
        predicted_class = class_names[np.argmax(score)]
        confidence = 100 * np.max(score)

        # -------- COLOR DETECTION -------- #
        dominant_rgb = get_dominant_item_color(filepath)
        color_name = rgb_to_color_name(dominant_rgb)

        prediction = f"Prediction: {predicted_class} ({confidence:.2f}%) - Color: {color_name}"

    return render_template('index.html', prediction=prediction, image_url=uploaded_image_url)

@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)

if __name__ == '__main__':
    os.makedirs('uploads', exist_ok=True)
    app.run(debug=True)
