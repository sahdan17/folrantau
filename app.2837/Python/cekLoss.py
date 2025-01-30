import streamlit as st
import streamlit.components.v1 as components

st.title('Pertamina Field Jambi')
st.subheader('Perhitungan Oil Losses')

def calculate_average(*args):
    # Filter out None or empty values
    valid_values = [arg for arg in args if arg is not None and arg != '']
    
    # Convert valid values to float
    valid_values = [float(value) for value in valid_values]
    
    if not valid_values:
        return None
    
    average = sum(valid_values) / len(valid_values)
    return average

def predict_loss(Rb1, Rb2, Rb3, Rb4, Rb5, Rb6, Tb1, Ab):
    avg = calculate_average(Rb1, Rb2, Rb3, Rb4, Rb5, Rb6)
    if avg is not None and Tb1 is not None and Ab is not None:
        Total = Tb1 * avg
        selisih = Total - Ab
        return selisih
    else:
        return None

# Arrange inputs in a three-column layout
col1, col2, col3 = st.columns(3)

with col1:
    TimeB1 = st.text_input('Durasi Shipping (Jam)', key='TimeB1')
    RateB1 = st.text_input('Rate (BBL/JAM) - 1', key='RateB1')
    RateB4 = st.text_input('Rate (BBL/JAM) - 4', key='RateB4')

with col2:
    AngkaBJG = st.text_input('Angka Shipping MGS (BBL)', key='AngkaBJG')
    RateB2 = st.text_input('Rate (BBL/JAM) - 2', key='RateB2')
    RateB5 = st.text_input('Rate (BBL/JAM) - 5', key='RateB5')

with col3:
    # Add refresh icon at the top of the column
    refresh_button_html = """
    <div style='display: flex; justify-content: flex-end;'>
        <button onclick="location.reload();" style="background: none; border: none; cursor: pointer;">
            <img src="https://img.icons8.com/ios-filled/50/000000/refresh.png" alt="Refresh" width="24" height="24">
        </button>
    </div>
    """
    components.html(refresh_button_html, height=30)
    
    RateB3 = st.text_input('Rate (BBL/JAM) - 3', key='RateB3')
    RateB6 = st.text_input('Rate (BBL/JAM) - 6', key='RateB6')

if st.button('Hitung Losses'):
    try:
        Tb1 = float(TimeB1) if TimeB1 else None
        Ab = float(AngkaBJG) if AngkaBJG else None
        Rb1 = float(RateB1) if RateB1 else None
        Rb2 = float(RateB2) if RateB2 else None
        Rb3 = float(RateB3) if RateB3 else None
        Rb4 = float(RateB4) if RateB4 else None
        Rb5 = float(RateB5) if RateB5 else None
        Rb6 = float(RateB6) if RateB6 else None

        Hitung_l = predict_loss(Rb1, Rb2, Rb3, Rb4, Rb5, Rb6, Tb1, Ab)
        
        if Hitung_l is not None:
            st.success(f"Banyaknya Oil losses adalah: {Hitung_l} BBL")
        else:
            st.warning("Input tidak valid untuk menghitung losses.")
    except Exception as e:
        st.error(f"Error predicting location: {e}")

# Custom styling to resemble a calculator
st.markdown(
    """
    <style>
    .stButton button {
        background-color: #4CAF50;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 12px;
    }
    .stTextInput input {
        font-size: 16px;
        padding: 10px;
        margin: 5px;
        border-radius: 12px;
        border: 1px solid #ccc;
    }
    </style>
    """,
    unsafe_allow_html=True
)
