import sys
import face_recognition
import json
import numpy as np

known_encoding = json.loads(sys.argv[1])
np_known_encoding = np.array(known_encoding)
unknown_encoding = json.loads(sys.argv[2])
np_unknown_encoding = np.array(unknown_encoding)

# Bandingkan dengan toleransi
results = face_recognition.compare_faces([np_known_encoding], np_unknown_encoding, tolerance=0)
print(results)

if results[0]:
    print("True")
else:
    print("False")
