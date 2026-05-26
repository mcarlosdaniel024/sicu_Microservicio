const express = require("express");
const fs = require("fs");

const app = express();

app.use(express.json());

app.post("/evento", (req, res) => {

    const evento = {
        ...req.body,
        fecha: new Date().toLocaleString()
    };

    let logs = [];

    if (fs.existsSync("logs.json")) {
        logs = JSON.parse(fs.readFileSync("logs.json"));
    }

    logs.push(evento);

    fs.writeFileSync("logs.json", JSON.stringify(logs, null, 2));

    console.log("Evento recibido:");
    console.log(evento);

    res.json({
        mensaje: "Evento registrado correctamente"
    });
});

app.listen(3000, () => {
    console.log("Microservicio activo en http://localhost:3000");
});