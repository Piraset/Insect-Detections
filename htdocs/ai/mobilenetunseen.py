import cv2
import os
import numpy as np
from keras.models import load_model

# Load the MobileNet model and class labels
model_path = os.path.abspath('./model4_model.h5')
model = load_model(model_path)
class_labels = ['ant', 'beetle', 'Rove beetles', 'Night Butterfly']

# Load the image
image = cv2.imread("./ai/DatasetProject/unseen/test1.jpg")
#image = cv2.imread("./ai/DatasetProject/unseen/unseen2.jpg")

# Create a region of interest (ROI) from the entire image
roi = image.copy()

# Convert the image to grayscale
gray = cv2.cvtColor(roi, cv2.COLOR_BGR2GRAY)

# Apply Gaussian blur to the grayscale image
gray_blur = cv2.GaussianBlur(gray, (15, 15), 0)

# Apply adaptive thresholding
thresh = cv2.adaptiveThreshold(gray_blur, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY_INV, 11, 1)

# Apply morphological operations (closing)
kernel = np.ones((3,3 ), np.uint8)
closing = cv2.morphologyEx(thresh, cv2.MORPH_CLOSE, kernel, iterations=4)

result_img = closing.copy()

# Find contours in the processed image
contours, hierarchy = cv2.findContours(result_img, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

counter = 0

# Loop through the detected contours
for cnt in contours:
    area = cv2.contourArea(cnt)
    
    # Adjust the area threshold as needed
    if 80 < area < 32000:
        # Extract the region of interest (ROI) for the detected insect
        x, y, w, h = cv2.boundingRect(cnt)
        insect_roi = roi[y:y+h, x:x+w]
        
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
        cv2.rectangle(roi, (x, y), (x+w, y+h), (0, 800, 0), 2)
        cv2.putText(roi, f"{predicted_class}", (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 0, 0), 2, cv2.LINE_AA)
        
        counter += 1

# Add a label for the total count
cv2.putText(roi, f"Total Insects Detected: {counter}", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 0, 0), 2, cv2.LINE_AA)

# Display the image with counted insects and labels
cv2.imshow("Counted Insects", roi)
cv2.waitKey(0)
cv2.destroyAllWindows()