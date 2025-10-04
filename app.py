from flask import Flask, request, render_template, send_from_directory, redirect, url_for
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
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
JSON_FILE = 'uploads.json'

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
    
    output = remove(input_image)  
    img_no_bg = Image.open(io.BytesIO(output)).convert("RGBA")
    
    img_rgb = Image.new("RGB", img_no_bg.size, (255, 255, 255))
    img_rgb.paste(img_no_bg, mask=img_no_bg.split()[3])  # alpha channel as mask

    img_rgb = img_rgb.resize((200, 200))
    pixels = list(img_rgb.getdata())

    filtered_pixels = [p for p in pixels if not (p[0] > 240 and p[1] > 240 and p[2] > 240)]
    if not filtered_pixels:
        filtered_pixels = pixels

    color_counts = Counter(filtered_pixels)
    dominant_color = color_counts.most_common(k)[0][0]
    return dominant_color


def rgb_to_color_name(rgb):
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


def load_data():
    if not os.path.exists(JSON_FILE):
        return {}
    with open(JSON_FILE, 'r') as f:
        return json.load(f)


def save_data(data):
    with open(JSON_FILE, 'w') as f:
        json.dump(data, f, indent=4)


# ---------- ROUTES ---------- #
@app.route('/')
def home():
    return render_template('home.html')


@app.route('/found', methods=['GET', 'POST'])
def found():
    prediction = ''
    uploaded_image_url = ''

    if request.method == 'POST':
        file = request.files.get('file')
        if not file or file.filename == '':
            prediction = 'No file selected'
            return render_template('found.html', prediction=prediction)

        # Rename and save file
        ext = file.filename.rsplit('.', 1)[-1].lower() if '.' in file.filename else 'jpg'
        new_filename = get_next_filename(ext)
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], new_filename)

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
        data = load_data()
        existing_numbers = [int(key) for key in data.keys() if key.isdigit()]
        next_id = max(existing_numbers) + 1 if existing_numbers else 1

        data[str(next_id)] = {
            "ImageName": new_filename,
            "ItemType": predicted_class,
            "Color": color_name,
            "DateTime": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        }

        save_data(data)

        prediction = f"Prediction: {predicted_class} ({confidence:.2f}%) - Color: {color_name}"

    return render_template('found.html', prediction=prediction, image_url=uploaded_image_url)


@app.route('/lost_items')
def lost_items():
    data = load_data()
    return render_template('lost_items.html', items=data)


@app.route('/item/<item_id>')
def item_detail(item_id):
    data = load_data()
    item = data.get(item_id)
    if not item:
        return "Item not found", 404
    return render_template('item_detail.html', item=item, item_id=item_id)


@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)


if __name__ == '__main__':
    os.makedirs('uploads', exist_ok=True)
    app.run(debug=True)
