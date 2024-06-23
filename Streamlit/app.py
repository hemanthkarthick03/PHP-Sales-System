import streamlit as st
import requests

# PHP endpoint URL
php_endpoint = 'http://127.0.0.1:8000/Streamlit/message.php'

# Function to fetch message from PHP endpoint
def fetch_message():
    try:
        response = requests.get(php_endpoint)
        if response.status_code == 200:
            data = response.json()
            return data.get('message', 'Error: No message found')
        else:
            return f"Error: {response.status_code} - {response.reason}"
    except requests.exceptions.RequestException as e:
        return f"Error: {e}"

# Streamlit app
def main():
    st.title('PHP and Streamlit Integration')
    
    # Fetch message from PHP
    message = fetch_message()
    
    # Display message
    st.write(f'Message from PHP: {message}')

if __name__ == '__main__':
    main()
