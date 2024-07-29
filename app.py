from flask import Flask, render_template, jsonify
import subprocess

app = Flask(__name__)

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/run-script', methods=['POST'])
def run_script():
    try:
        # Path to your Python script
        script_path = r'C:\xampp\htdocs\examseater\sorting6.py'
        
        # Run the Python script
        subprocess.Popen(['python', script_path])
        
        # If the script runs successfully, return a success message
        return jsonify(message="done"), 200
    except Exception as e:
        # If there's an error, return an error message
        return jsonify(message=str(e)), 500

if __name__ == '__main__':
    app.run(debug=True)
