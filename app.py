from flask import Flask, request, render_template, send_from_directory
import tensorflow as tf
import keras
from keras.preprocessing import image
import numpy as np
import os
from collections import Counter
from PIL import Image
from rembg import remove
import io
import json
from datetime import datetime

app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
JSON_FILE = 'uploads.json'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Load trained model
model = keras.models.load_model('mobilenet_item_classifier.h5')

# Class names
class_names = ['keyboard', 'keys', 'laptop', 'mouse', 'phone', 'usb', 'headphones']
IMG_SIZE = (224, 224)

# ---------- COLOR DETECTION HELPERS ---------- #
def get_dominant_item_color(image_path, k=3):
    """Remove background, then find dominant color of the item."""
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


def get_next_filename(extension="jpg"):
    """Find the next available ascending number filename in uploads folder."""
    existing_files = [f for f in os.listdir(UPLOAD_FOLDER) if f.split('.')[0].isdigit()]
    
    if not existing_files:
        next_num = 1
    else:
        numbers = [int(f.split('.')[0]) for f in existing_files if f.split('.')[0].isdigit()]
        next_num = max(numbers) + 1
    
    return f"{next_num}.{extension}"


def save_to_json(image_name, item_type, color):
    """Save upload details to a JSON file with ascending Upload_N keys."""
    data = {}

    # Load existing JSON if available
    if os.path.exists(JSON_FILE):
        with open(JSON_FILE, "r") as f:
            try:
                data = json.load(f)
            except json.JSONDecodeError:
                data = {}

    # Determine next Upload_N key
    if data:
        existing_numbers = [int(key) for key in data.keys() if key.isdigit()]
        next_num = max(existing_numbers) + 1
    else:
        next_num = 1

    data[next_num] = {
        "ImageName": image_name,
        "ItemType": item_type,
        "Color": color,
        "DateTime": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    }

    # Save updated JSON
    with open(JSON_FILE, "w") as f:
        json.dump(data, f, indent=4)


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

        # Get file extension
        ext = file.filename.rsplit('.', 1)[-1].lower() if '.' in file.filename else 'jpg'
        new_filename = get_next_filename(ext)
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], new_filename)

        # Save renamed file
        file.save(filepath)
        uploaded_image_url = f'/uploads/{new_filename}'

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

        # -------- SAVE TO JSON -------- #
        save_to_json(new_filename, predicted_class, color_name)

        prediction = f"Prediction: {predicted_class} ({confidence:.2f}%) - Color: {color_name}"

    return render_template('index.html', prediction=prediction, image_url=uploaded_image_url)


@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)


if __name__ == '__main__':
    os.makedirs('uploads', exist_ok=True)
    app.run(debug=True)
