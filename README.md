# Proyecto Symfony - Prueba Amazon

Este proyecto es un ejemplo de aplicación **Symfony 6+** con **MongoDB**, Docker y un sistema de ratings aleatorios para productos. Permite ver una lista de productos con puntuaciones, estrellas y etiquetas, además de soporte de login/logout.

---

## Instalación y levantamiento con Docker

1. Clona el repositorio:

```bash
git clone https://github.com/CarlesMR/prueba-amazon.git
cd prueba-amazon
```

2. Levanta los contenedores:

```bash
docker compose up -d
```

Servicios levantados:

- `app` → contenedor PHP/Symfony
- `mongo` → base de datos MongoDB
- `nginx` → servidor web (opcional, si está configurado)

3. Accede al contenedor de Symfony para ejecutar comandos:

```bash
docker exec -it prueba-amazon bash
```

---

## Configuración de entorno

Copia el archivo de ejemplo `.env.example` a `.env`:

```bash
cp .env.dev .env
```

Asegúrate de configurar los parámetros según tu entorno, especialmente la conexión a MongoDB:

```dotenv
MONGODB_URL="mongodb://root:rootpass@mongo:27017/appdb?authSource=admin"
MONGODB_DB="appdb"
APP_ENV=dev
```

4. Instalación de composer:

```bash
composer install
```

5. Generar APP_SECRET:

Primero generamos un string aleatorio:

```bash
openssl rand -hex 32
```

Copiamos el valor devuelto en .env en la clave `APP_SECRET`.

---

## Crear la base de datos

Si usas MongoDB ODM, crea la base de datos y las collections necesarias:

```bash
php bin/console doctrine:mongodb:schema:create
```

---

## Ejecutar Seeder

Para insertar datos de prueba (usuarios y productos):

```bash
php bin/console app:seed
```

> Esto generará un usuario con credenciales por defecto para poder loguearse.

---

## Acceder a la aplicación

- URL principal: [http://localhost:8080](http://localhost:8080)
- Ruta de login: `/login`
- Ruta de logout: `/logout`

> Una vez logueado, el usuario aparecerá en la barra de navegación y se podrá acceder a la lista de productos con ratings aleatorios.
