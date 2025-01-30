import sys

def predict_location(x1, x2):
    y = 23.21 - 2.76 * x1 - 0.01 * x2
    return y

def main(Titik_1_PSI, Titik_2_PSI):
    try:
        x1 = float(Titik_1_PSI)
        x2 = float(Titik_2_PSI)
        prediksi_lokasi = predict_location(x1, x2)
                
        if prediksi_lokasi <= 0: # titik nol
            suspect_loct = 'Pipa Aman, Tidak Terdapat Fluida yang Mengalir'
        elif prediksi_lokasi >= 23.1: # total panjang trunkline
            suspect_loct = 'Tidak Terdapat Kebocoran'
        else:
            suspect_loct = f'Terjadi kebocoran di titik {prediksi_lokasi} KM'
        print(suspect_loct)
    except Exception as e:
        print(f"Error predicting location: {e}")

if __name__ == "__main__":
    if len(sys.argv) >= 3:
        Titik_1_PSI = sys.argv[1]
        Titik_2_PSI = sys.argv[2]
        main(Titik_1_PSI, Titik_2_PSI)
    else:
        print("Not enough arguments provided.")
