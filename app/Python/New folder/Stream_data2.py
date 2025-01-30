import sys

def predict_loss(R1, P1, P2, s):
    try:
        R2 = P2 * R1 / P1
        los = R2 - R1
        y = los * s
        if y < 0:
            return f'Terjadi losses sebesar {y} BBL'
        elif y > 0:
            return f'Gain sebesar {y} BBL'
        else:
            return 'Tidak terjadi losses'
    except Exception as e:
        return f"Error predicting loss: {e}"

if __name__ == "__main__":
    if len(sys.argv) >= 3:
        Rate1 = int(sys.argv[1])
        Pressure1 = int(sys.argv[2])
        Pressure2 = int(sys.argv[3])
        Durasi = float(sys.argv[4])
        
        print(predict_loss(Rate1, Pressure1, Pressure2, Durasi))
    else:
        print("Not enough arguments provided.")