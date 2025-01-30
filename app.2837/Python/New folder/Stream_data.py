import pickle
import sys

def load_model(file_path):
    try:
        with open(file_path, 'rb') as file:
            model = pickle.load(file)
        return model
    except Exception as e:
        print(f"Error loading the model: {e}")
        return None

def predict_location(model, Titik_1_PSI, Titik_2_PSI):
    try:
        prediksi_lokasi = model.predict([[float(Titik_1_PSI), float(Titik_2_PSI)]])
        if prediksi_lokasi[0] == 0:
            return 'It is safe that there is no fluid flowing'
        elif prediksi_lokasi[0] >= 23.1:
            return 'Safe, there are no leaks'
        else:
            return f'Estimated leak location {prediksi_lokasi[0]} KM'
    except Exception as e:
        return f"Error predicting location: {e}"

if __name__ == "__main__":
    model = load_model('../app/Python/Pred_lokasi11.sav')

    if model is not None:
        if len(sys.argv) >= 3:
            Titik_1_PSI = sys.argv[1]
            Titik_2_PSI = sys.argv[2]
            
            print(predict_location(model, Titik_1_PSI, Titik_2_PSI))
        else:
            print("Not enough arguments provided.")
    else:
        print("Model tidak dapat dimuat.")
