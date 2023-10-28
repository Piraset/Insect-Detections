import os
import numpy as np
import tensorflow as tf
from keras.applications import MobileNetV2
from keras.layers import GlobalAveragePooling2D, Dense, Dropout, BatchNormalization
from keras.models import Model
from keras.optimizers import Adam
from keras.callbacks import LearningRateScheduler, EarlyStopping
from keras.preprocessing.image import ImageDataGenerator
import matplotlib.pyplot as plt

train_dir = './ai/DatasetProject/train'
test_dir = './ai/DatasetProject/test'

# Count the number of subdirectories (classes) in the training directory
num_classes = len(os.listdir(train_dir))

# Create a MobileNetV2 base model
base_model = MobileNetV2(weights='imagenet', include_top=False, input_shape=(500,500, 3))

# Freeze the base model layers
for layer in base_model.layers:
    layer.trainable = False

# Add custom layers for classification
x = base_model.output
x = GlobalAveragePooling2D()(x)
x = Dense(255, activation='relu')(x)
x = BatchNormalization()(x)
x = Dropout(0.2)(x)  # Add dropout for regularization
predictions = Dense(num_classes, activation='softmax')(x)

# Create the final model
model = Model(inputs=base_model.input, outputs=predictions)

# Compile the model
model.compile(optimizer=Adam(learning_rate=0.0001),  # Adjust the learning rate as needed
              loss='categorical_crossentropy',
              metrics=['accuracy'])

# Define a learning rate schedule
def lr_schedule(epoch):
    if epoch < 60:
        return 0.0001
    elif epoch < 80:
        return 0.0001
    else:
        return 0.00001

lr_scheduler = LearningRateScheduler(lr_schedule)

# Define early stopping
early_stopping = EarlyStopping(monitor='val_loss', patience=100, restore_best_weights=True)

# Create data generators
train_datagen = ImageDataGenerator(
    rescale=1./800,
    rotation_range=20,
    width_shift_range=0.7,
    height_shift_range=0.7,
    shear_range=0.7,
    zoom_range=0.7,
    horizontal_flip=True,
    vertical_flip=True,
    fill_mode='nearest'
)

test_datagen = ImageDataGenerator(rescale=1./800)

train_data = train_datagen.flow_from_directory(
    train_dir,
    target_size=(800, 800),
    batch_size=10,
    class_mode='categorical'
)

test_data = test_datagen.flow_from_directory(
    test_dir,
    target_size=(800, 800),
    batch_size=10,
    class_mode='categorical'
)

# Train the model
history = model.fit(
    train_data,
    epochs=80,  # Adjust the number of epochs as needed
    validation_data=test_data,
    callbacks=[lr_scheduler, early_stopping]
)

# Save the model
model.save('model4_model.h5')

# Plot training history
plt.figure(figsize=(10, 4))

plt.subplot(1, 2, 1)
plt.plot(history.history['accuracy'], label='Training Accuracy')
plt.xlabel('Epoch')
plt.ylabel('Accuracy')
plt.legend()

plt.subplot(1, 2, 2)
plt.plot(history.history['loss'], label='Training Loss')
plt.xlabel('Epoch')
plt.ylabel('Loss')
plt.legend()

plt.tight_layout()
plt.show()
