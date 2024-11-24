import sys
import face_recognition
import json

def main(image_path, image_path_exist):
    # Load gambar
    image = face_recognition.load_image_file(image_path)
    imageExist = face_recognition.load_image_file(image_path_exist)

    # Encode wajah
    encodings = face_recognition.face_encodings(image)[0]
    encodingsExist = face_recognition.face_encodings(imageExist)[0]

    results = face_recognition.compare_faces([encodings], encodingsExist)

    if results[0] == True:
        print(json.dumps("true"))
    else:
        print(json.dumps("false"))

if __name__ == "__main__":
    image_path = sys.argv[1]
    image_path_exist = sys.argv[2]
    main(image_path, image_path_exist)
