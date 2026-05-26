
# SICU - Microservicios

Este proyecto fue adaptado desde un sistema monolítico PHP a una arquitectura inicial basada en microservicios.

## Servicios

- Frontend Principal → http://localhost:8080
- Auth Service → http://localhost:8081
- Entry Service → http://localhost:8082
- Exit Service → http://localhost:8083
- Audit Service → http://localhost:3000

## Requisitos

- Docker Desktop

## Cómo ejecutar

Abrir terminal en la carpeta y ejecutar:

docker-compose up --build

## Base de datos

La base de datos se importa automáticamente desde:

database/sicu_db.sql

## Nota

El frontend original fue preservado dentro del servicio frontend.
