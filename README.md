# Logrocho Backend

Backend escrito en PHP para la plataforma "Logrocho".

## Requisitos

1. PHP >= 7.4.26

2. Composer

3. Servidor XAMPP o WAMPP instalado localmente.

## Iniciar Proyecto

1. Colocar carpeta en un servidor XAMPP o WAMP.

2. Abrir una terminal en el proyecto y ejecutar el siguiente comando.

```bash
composer install
```

## Endpoints

Ruta por defecto: {XAMPP or WAMPP}/logrocho-backend/index.php/api

### User Endpoints

- /login:

    - Descripcion: Comprueba que el usuario exista en la bd.

    - Paramentros: Body con el objeto `{email:string, password:string}`

    ```json
    {
        "email":"correo@test.com",
        "password":"admin"
    }
    ```

    - Respuesta: Si el usuario existe en la bd se devuelve un objeto `{status:boolean, data:string}` siendo "data" el token con la informacion del usuario.

    ```json
    {
        "status": true,
        "data": "eyJ0eXAiOiJKV1QiLCJhbGciO..."
    }
    ```

-----

### Bar Endpoints

- /bares:

    - Descripcion: Devuelve una lista de bares.

    - Paramentros: Parametro GET "?page" con la pagina de bares que quieres obtener (Cada pagina esta formada por 10 bares).

        `{Ruta por defecto}/bares?page=0`

    - Respuesta: Objeto `{status:boolean, data: Bar[]}` con los bares obtenidos para dicha pagina.

    ```json
    {
        "status": true,
        "data": [
            {
                "id": "1",
                "nombre": "Bar 1",
                "localizacion": "Calle Laurel 12",
                "informacion": "El mejor bar de la calle"
            },
        ...]
    }
    ```

- /bar:

    - Descripcion: Devuelve un bar para una clave primaria.

    - Parametros: Parametro GET "?id" con el id del bar que quiero obtener.

        `{Ruta por defecto}/bar?id=1`

    - Respuesta: Objeto `{status:boolean, data:Bar}` con el bar obtenido.

    ```json
    {
        "status": true,
        "data": {
            "id": "1",
            "nombre": "Bar 1",
            "localizacion": "Calle Laurel 12",
            "informacion": "El mejor bar de la calle"
        }
    }
    ```
- /updateBar:

    ⚠️ **Accion exclusiva de usuarios con el rol admin** ⚠️

    - Decripcion: Actualiza la informacion de un bar.

    - Paramentros: Body con el objeto `Bar` y Bearer Token con el token que obtenemos al logearnos.

    ```json
    {
        "id":"1",
        "nombre":"Bar 1",
        "localizacion":"Calle Laurel 12",
        "informacion":"El mejor bar de la calle"
    }
    ```

    - Respuesta: Objeto `{status:boolean, message:string}` indicando si el bar se ha podido actualizar correctamente o no.

- /insertBar:

    ⚠️ **Accion exclusiva de usuarios con el rol admin** ⚠️

    - Descripcion: Añade un nuevo bar a la tabla.

    - Parametros: Body con el objeto `{nombre:string, localizacion:string, informacion:string}` y Bearer Token con el token que obtenemos al logearnos.

    ```json
    {
        "nombre":"Bar 1",
        "localizacion":"Calle Laurel 12",
        "informacion":"El mejor bar de la calle"
    }
    ```

    - Respuesta: Objeto `{status:boolean, message:string}` indicando si el bar se ha podido insertar correctamente o no.

- /deleteBar:

    ⚠️ **Accion exclusiva de usuarios con el rol admin** ⚠️

    - Descripcion: Eliminar un bar de la tabla.

    - Parametros: Body con el id del bar y Bearer Token con el token que obtenemos al logearnos.

    ```json
    {
        "id":"1"
    }
    ```

    - Respuesta: Objeto `{status:boolean, message:string}` indicando si el bar se ha podido eliminar correctamente o no.

-----

### Pincho Endpoints

- /pinchos:

    - Descripcion: Devuelve una lista de pinchos.

    - Paramentros: Parametro GET "?page" con la pagina de pinchos que quieres obtener (Cada pagina esta formada por 10 pinchos).

        `{Ruta por defecto}/pinchos?page=0`

    - Respuesta: Objeto `{status:boolean, data: Pincho[]}` con los pinchos obtenidos para dicha pagina.

    ```json
    {
        "status": true,
        "data": [
            {
                "id": "1",
                "nombre": "El zorropito",
                "puntuacion": "1",
                "ingredientes": "Ingrediente 1, Ingrediente 2, Ingrediente 3, Ingrediente 4"
            },
        ...]
    }
    ```

- /pincho:

    - Descripcion: Devuelve un pincho para una clave primaria dada.

    - Parametros: Parametro GET "?id" con el id del pincho que quiero obtener.

        `{Ruta por defecto}/pincho?id=1`

    - Respuesta: Objeto `{status:boolean, data:Pincho}` con el pincho obtenido.

    ```json
    {
        "status": true,
        "data": {
            "id": "1",
            "nombre": "El zorropito",
            "puntuacion": "1",
            "ingredientes": "Ingrediente 1, Ingrediente 2, Ingrediente 3, Ingrediente 4"
        }
    }
    ```
- /updatePincho:

    ⚠️ **Accion exclusiva de usuarios con el rol admin** ⚠️

    - Decripcion: Actualiza la informacion de un pincho.

    - Paramentros: Body con el objeto `Pincho` y Bearer Token con el token que obtenemos al logearnos.

    ```json
    {
        "id": "1",
        "nombre": "El zorropito 2",
        "puntuacion": 1,
        "ingredientes": "Ingrediente 1, Ingrediente 2, Ingrediente 3, Ingrediente 4"
    }
    ```

    - Respuesta: Objeto `{status:boolean, message:string}` indicando si el pincho se ha podido actualizar correctamente o no.

- /insertPincho:

    ⚠️ **Accion exclusiva de usuarios con el rol admin** ⚠️

    - Descripcion: Añade un nuevo pincho a la tabla.

    - Parametros: Body con el objeto `{nombre:string, puntuacion:number, ingredientes:string}` y Bearer Token con el token que obtenemos al logearnos.

    ```json
    {
        "nombre": "Tortilla de patatas",
        "puntuacion": "10",
        "ingredientes": "Ingrediente 1, Ingrediente 2, Ingrediente 3, Ingrediente 7"
    }
    ```

    - Respuesta: Objeto `{status:boolean, message:string}` indicando si el pincho se ha podido insertar correctamente o no.

- /deletePincho:

    ⚠️ **Accion exclusiva de usuarios con el rol admin** ⚠️

    - Descripcion: Eliminar un pincho de la tabla.

    - Parametros: Body con el id del pincho y Bearer Token con el token que obtenemos al logearnos.

    ```json
    {
        "id":"1"
    }
    ```

    - Respuesta: Objeto `{status:boolean, message:string}` indicando si el pincho se ha podido eliminar correctamente o no.
