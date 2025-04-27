# Pokémon Card Collection and Exchange Platform

## Overview
This project is a comprehensive platform for collecting and trading Pokémon cards. It features a user-friendly interface, robust backend functionality, and IoT integration for enhanced user experiences. The platform allows users to register, log in, manage their card collections, and engage in trades with other users.

## Features
- **User Authentication**: Secure registration and login processes.
- **Card Collection Management**: Users can view, add, and manage their Pokémon cards.
- **Trading System**: Users can propose and accept trades with others.
- **Responsive Design**: The platform is designed to be accessible on various devices.
- **Theming Support**: Users can switch between different themes for a personalized experience.
- **IoT Integration**: Interaction with IoT devices for real-time card functionalities.

## Project Structure
```
pokemon-card-platform
├── assets
│   ├── css
│   │   ├── styles.css
│   │   └── themes.css
│   ├── js
│   │   ├── app.js
│   │   ├── api.js
│   │   └── ui.js
│   ├── images
│   │   └── placeholder.txt
│   └── fonts
│       └── placeholder.txt
├── backend
│   ├── api
│   │   ├── cards.php
│   │   ├── users.php
│   │   └── trades.php
│   ├── database
│   │   ├── db_connection.php
│   │   └── migrations
│   │       └── create_tables.sql
│   └── helpers
│       └── utils.php
├── iot
│   ├── device
│   │   ├── firmware.ino
│   │   └── config.json
│   └── server
│       ├── iot_server.js
│       └── mqtt_config.json
├── pages
│   ├── index.html
│   ├── login.html
│   ├── register.html
│   ├── dashboard.html
│   └── trade.html
├── .env
├── .gitignore
├── README.md
└── package.json
```

## Installation
1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd pokemon-card-platform
   ```
3. Install dependencies:
   ```
   npm install
   ```
4. Set up the database by running the SQL migration file located in `backend/database/migrations/create_tables.sql`.
5. Configure environment variables in the `.env` file.

## Usage
- Start the backend server using:
  ```
  php -S localhost:8000 -t backend
  ```
- Open the `pages/index.html` file in your web browser to access the platform.

## Contributing
Contributions are welcome! Please submit a pull request or open an issue for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for more details.