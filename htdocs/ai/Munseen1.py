import cv2
import numpy as np
import mysql.connector
from keras.models import load_model
import io
import os
from PIL import Image
import base64


# Load the MobileNet model and class labels
model_path = './ai/model2_model.h5'  # Replace with your model path
model = load_model(model_path)
class_labels = ['ant', 'beetle', 'Rove beetles', 'Night Butterfly']

# Connect to the MySQL database
db_connection = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="1234",
    database="datasave1"
)

# Check if the database connection is successful
# Check if the database connection is successful
if db_connection.is_connected():
    cursor = db_connection.cursor(dictionary=True)
else:
    print("Database connection failed")


# Define a SQL query to retrieve the latest image data from the database
query = "SELECT image_data FROM images ORDER BY id DESC LIMIT 1"



# Execute the query
cursor.execute(query)

# Create a function to process each image
def process_latest_image(image_data):
    # Convert the binary image data to a format usable by OpenCV
    image = Image.open(io.BytesIO(image_data))
    image = cv2.cvtColor(np.array(image), cv2.COLOR_RGB2BGR)
    model_path = os.path.abspath('./ai/model2_model.h5')
    model = load_model(model_path)
    class_labels = ['ant', 'beetle', 'Rove beetles', 'Night Butterfly']
    roi = image.copy()

    # Initialize the counter
    counter = 0

    # Convert the image to grayscale
    gray = cv2.cvtColor(roi, cv2.COLOR_BGR2GRAY)

    # Apply Gaussian blur to the grayscale image
    gray_blur = cv2.GaussianBlur(gray, (15, 15), 0)

    # Apply adaptive thresholding
    thresh = cv2.adaptiveThreshold(gray_blur, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY_INV, 11, 1)

    # Apply morphological operations (closing)
    kernel = np.ones((3, 3), np.uint8)
    closing = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, kernel, iterations=4)

    result_img = closing.copy()

    # Find contours in the processed image
    contours, hierarchy = cv2.findContours(result_img, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    # Loop through the detected contours and count them
    for cnt in contours:
        area = cv2.contourArea(cnt)

        # Adjust the area threshold as needed
        if 80 < area < 32000:
            # Extract the region of interest (ROI) for the detected insect
            x, y, w, h = cv2.boundingRect(cnt)
            insect_roi = roi[y:y + h, x:x + w]

            # Preprocess the insect_roi for MobileNet input
            insect_roi = cv2.resize(insect_roi, (800, 800))
            insect_roi = cv2.cvtColor(insect_roi, cv2.COLOR_BGR2RGB)
            insect_roi = insect_roi.astype('float32') / 800
            insect_roi = np.expand_dims(insect_roi, axis=0)

            # Make sure that the model is not None
            if model is not None:
                # Use the MobileNet model to predict the insect class
                predictions = model.predict(insect_roi)
                predicted_class_index = np.argmax(predictions)
                predicted_class = class_labels[predicted_class_index]
            else:
                print("The model is None")
                continue

            # Draw a bounding box and label for the detected insect
            cv2.rectangle(roi, (x, y), (x + w, y + h), (0, 800, 0), 2)
            cv2.putText(roi, f"{predicted_class}", (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0), 2,
                        cv2.LINE_AA)

            # Increment the counter
            counter += 1

    # Add a label for the total count
    cv2.putText(roi, f"Total Insects Detected: {counter}", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 0, 0), 2,
                cv2.LINE_AA)
    
    
    # Check the current time
    ##current_time = datetime.datetime.now().time()
    #if current_time >= datetime.time(7, 0):
        #cv2.waitKey(0)  # Stop processing if it's 07:00 AM or later
    #else:
       # cv2.waitKey(3600000)  # Wait for 1 hour (3600000 milliseconds) before processing the next image

    # Convert the processed image back to binary data
    _, img_encoded = cv2.imencode(".jpg", roi)
    img_bytes = img_encoded.tobytes()

    # Convert the binary data to Base64
    img_base64 = base64.b64encode(img_bytes).decode("utf-8")

    # Insert the processed image into the 'Detected_blobs' table along with the counter value
    insert_query = f"INSERT INTO detected_blob (image1, Num) VALUES (%s, %s)"
    cursor.execute(insert_query, (img_bytes, counter))
    db_connection.commit()

    return image

# Loop through the result set and process each image
for row in cursor:
    image_data = row["image_data"]
    process_latest_image(image_data)

# Close the database connection
cursor.close()
db_connection.close()

    # Now you can proceed with further processing or display the processed image as needed
   
