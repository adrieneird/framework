# Minimalist MVC PHP Framework

## Purpose

- Solo dev
- KISS
- Ship fast

## Architecture / Workflow

- Index calls router
- Router checks controller class and execute it's static action method
- Controller class might verify auth, might create a form object, might create a model object or call a model static method
- Form class loads data from request (if posted), validate data, process validated data, renders form
- Model is both form's DTO and active record
- Controller class sends response (html, redirect)

## Design choices
- Static methods, FUCK YEAH
- If a class doesn't have multiple instances, doesn't have attributes, is stateless, it doesn't need to be instanciated
- No .env (config.php works fine)
- No Auth/RBAC (Controllers manage access)
- No DTO (Models are DTO)
- No interfaces (solo dev)
- DB singleton
- Form have inputs
- Form manages CSRF
- Form manages validation (through inputs)
- Form Create/Update Data flow : POST > form inputs > form DTO > DB (Model save)
- Form Read Data flow : DB (Model find) > form DTO > form inputs
- No tests
- Simple is stupid, yet elegant

## Setup
- Configure config.php
- Launch install.php on your server