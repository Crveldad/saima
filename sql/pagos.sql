CREATE DATABASE IF NOT EXISTS pagos;

USE pagos;

CREATE TABLE IF NOT EXISTS TransaccionTarjeta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numeroTarjeta VARCHAR(20) NOT NULL,
    respuestaBanco TINYINT(1) NOT NULL
);

CREATE TABLE IF NOT EXISTS TransaccionEfectivo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monedas TEXT NOT NULL,
    devolucion TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS Transaccion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cantidad FLOAT NOT NULL,
    status ENUM('ok', 'ko') NOT NULL,
    transaccion_tarjeta_id INT DEFAULT NULL,
    transaccion_efectivo_id INT DEFAULT NULL,
    FOREIGN KEY (transaccion_tarjeta_id) REFERENCES TransaccionTarjeta(id),
    FOREIGN KEY (transaccion_efectivo_id) REFERENCES TransaccionEfectivo(id)
);