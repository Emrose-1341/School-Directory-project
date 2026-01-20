from flask import Flask, render_template, request, redirect, url_for, session, flash
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = "supersecretkey"

# Example users
users = {
    "faculty": generate_password_hash("faculty123"),
    "student": generate_password_hash("student123")
}

roles = {
    "faculty": "faculty",
    "student": "student"
}

@app.route("/", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        username = request.form["username"]
        password = request.form["password"]

        if username in users and check_password_hash(users[username], password):
            session["username"] = username
            session["role"] = roles[username]
            return redirect(url_for("dashboard"))
        else:
            flash("Invalid username or password")
            return redirect(url_for("login"))

    return render_template("login.html")

@app.route("/dashboard")
def dashboard():
    if "role" not in session:
        return redirect(url_for("login"))

    role = session["role"]
    if role == "faculty":
        return f"Welcome Faculty {session['username']}! You can upload materials."
    elif role == "student":
        return f"Welcome Student {session['username']}! You can view assignments."
    else:
        return "Access restricted."

@app.route("/logout")
def logout():
    session.clear()
    return redirect(url_for("login"))

if __name__ == "__main__":
    app.run(debug=True)
