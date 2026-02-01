# Minimalist MVC PHP Framework

## Purpose

- Solo dev
- KISS
- Ship fast

## Architecture / Workflow

- Index calls router
- Router instanciate controller class and execute it's action method
- Controller class might verify auth, might create a form object, might create a model object or call a model static method
- Form class loads data from request (if posted), validate data, process validated data, renders form
- Model is both form's DTO and active record
- Controller class sends response (html, redirect)

## Design choices
- Static methods, FUCK YEAH
- No .env (globals.php works fine)
- No Auth/RBAC (Controllers manage access)
- No DTO (Models are DTO)
- No interfaces (solo dev)
- DB singleton
- Form manages CSRF
- Form manages validation
- No tests
- Simple is stupid, yet elegant

## Setup
- Configure globals.php
- Launch install.php on your server