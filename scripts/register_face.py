import sys
import face_recognition
import json

def main(image_path):
    # Load gambar
    image = face_recognition.load_image_file(image_path)

    # Encode wajah
    encodings = face_recognition.face_encodings(image)
    if encodings:
        print(json.dumps(encodings[0].tolist()))  # Encode sebagai JSON
    else:
        print(json.dumps(['wajah tidak ditemukan']))  # Tidak ada wajah ditemukan

if __name__ == "__main__":
    image_path = sys.argv[1]
    main(image_path)
