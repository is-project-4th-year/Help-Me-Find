from flask import Flask, request, render_template, send_from_directory
import tensorflow as tf
import keras
from keras.preprocessing import image
import numpy as np
import os

app = Flask(__name__)
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

model = keras.models.load_model('mobilenet_item_classifier.h5')
class_names = ['keyboard', 'keys', 'laptop', 'mouse', 'phone', 'usb']  # Update as per your dataset. add 'headphones'
IMG_SIZE = (224, 224)


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

        # Load and preprocess image
        img = image.load_img(filepath, target_size=IMG_SIZE)
        img_array = image.img_to_array(img)
        img_array = tf.expand_dims(img_array, 0)

        # Predict
        predictions = model.predict(img_array)
        score = tf.nn.softmax(predictions[0])
        predicted_class = class_names[np.argmax(score)]
        confidence = 100 * np.max(score)

        prediction = f"Prediction: {predicted_class} ({confidence:.2f}%)"

    return render_template('index.html', prediction=prediction, image_url=uploaded_image_url)

@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)

if __name__ == '__main__':
    os.makedirs('uploads', exist_ok=True)
    app.run(debug=True)
