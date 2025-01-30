import sys

def predict_location(x1):
    y = 13.08 - 3.93 * x1
    return y

def main(Titik_1_PSI):
    try:
        x1 = float(Titik_1_PSI)
        prediksi_lokasi = predict_location(x1)
        
        if prediksi_lokasi <= 0: # titik nol
            suspect_loct = 'Pipa Aman, Tidak Terdapat Fluida yang Mengalir'
        elif prediksi_lokasi >= 11.25: # total panjang trunkline
            suspect_loct = 'Tidak Terdapat Kebocoran'
        else:
            suspect_loct = f'Terjadi kebocoran di titik {prediksi_lokasi} KM'
        print(suspect_loct)
    except Exception as e:
        print(f"Error predicting location: {e}")

if __name__ == "__main__":
    if len(sys.argv) >= 2:
        Titik_1_PSI = sys.argv[1]
        main(Titik_1_PSI)
    else:
        print("Not enough arguments provided.")
