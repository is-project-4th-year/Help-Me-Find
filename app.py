from flask import Flask, request, render_template, send_from_directory, redirect, url_for
import os
import json
from datetime import datetime
from google import genai
from google.genai import types
from dotenv import load_dotenv

load_dotenv()

app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
JSON_FILE = 'uploads.json'


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


# ---------- AI DESCRIPTION FUNCTION ---------- #
def generate_description_with_ai(image_path):

    # 1. Define prompt
    prompt = "Describe exactly what the item in this image is in 50 words or less. Be concise and neutral."

    # 2. Read image bytes
    with open(image_path, 'rb') as f:
        image_bytes = f.read()

    try:
        client = genai.Client()
        response = client.models.generate_content(
            model="gemini-2.0-flash",
            contents=[
                types.Part.from_bytes(
                    data=image_bytes,
                    mime_type='image/jpeg',
                ),
                prompt
            ],
        )
    except Exception as e:
        print(f"Gemini API call failed: {e}")
        return "Automatic description failed."

    return response.text


@app.route('/')
def home():
    return render_template('home.html')


@app.route('/found', methods=['GET', 'POST'])
def found():
    uploaded_image_url = ''
    item_description = ''

    if request.method == 'POST':
        file = request.files.get('file')
        if not file or file.filename == '':
            return render_template('found.html', error='No file selected')

        # Rename and save file
        ext = file.filename.rsplit('.', 1)[-1].lower() if '.' in file.filename else 'jpg'
        new_filename = get_next_filename(ext)
        filepath = os.path.join(app.config['UPLOAD_FOLDER'], new_filename)

        file.save(filepath)
        uploaded_image_url = f'/uploads/{new_filename}'

        # Generate AI description (if available)
        item_description = generate_description_with_ai(filepath)

        # Save minimal data to JSON (ImageName, Description, DateTime)
        data = load_data()
        existing_numbers = [int(key) for key in data.keys() if key.isdigit()]
        next_id = max(existing_numbers) + 1 if existing_numbers else 1

        data[str(next_id)] = {
            "ImageName": new_filename,
            "Description": item_description,
            "DateTime": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        }

        save_data(data)

    return render_template('found.html', image_url=uploaded_image_url, item_description=item_description)


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
