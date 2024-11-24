import sys
import face_recognition
import json

# Ambil path gambar dari argumen
image_path = sys.argv[1]

# Load gambar
image = face_recognition.load_image_file(image_path)

# Dapatkan encoding wajah
face_encodings = face_recognition.face_encodings(image)

if len(face_encodings) > 0:
    # Keluarkan encoding sebagai JSON
    print(json.dumps(face_encodings[0].tolist()))
else:
    print("Error: No face found", file=sys.stderr)
    sys.exit(1)
