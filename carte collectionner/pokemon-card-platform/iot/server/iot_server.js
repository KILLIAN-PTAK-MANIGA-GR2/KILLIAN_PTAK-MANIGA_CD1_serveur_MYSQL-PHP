const express = require('express');
const mqtt = require('mqtt');
const bodyParser = require('body-parser');

const app = express();
const port = process.env.PORT || 3000;

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

const mqttClient = mqtt.connect('mqtt://broker.hivemq.com');

mqttClient.on('connect', () => {
    console.log('Connected to MQTT broker');
    mqttClient.subscribe('pokemon/cards', (err) => {
        if (!err) {
            console.log('Subscribed to topic: pokemon/cards');
        }
    });
});

mqttClient.on('message', (topic, message) => {
    console.log(`Received message on ${topic}: ${message.toString()}`);
    // Handle incoming messages from IoT devices
});

app.post('/api/iot/card', (req, res) => {
    const cardData = req.body;
    mqttClient.publish('pokemon/cards', JSON.stringify(cardData), (err) => {
        if (err) {
            return res.status(500).json({ error: 'Failed to publish message' });
        }
        res.status(200).json({ message: 'Card data sent to IoT device' });
    });
});

app.listen(port, () => {
    console.log(`IoT server running on http://localhost:${port}`);
});