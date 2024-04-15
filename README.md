# Bridge Framework

### Lifecycle

-   0: **Run App** Antes de ejecutar el kernel (constantes o set sobre singleton), durante el run de la aplicación, aunque contiene el resto de la ejecución.
-   1: **Set Kernel Constants** Establecer constantes generales de rutas e inicio del debug run time sobre la app.
-   2: **Load Functions File** Cargar los archivos de Helpers, estos a su vez cargan y establecen ciertos paths para el manejo de archivos sobre la aplicación.
-   3: **Load DotEnv** Carga el archivo .env para las variables de entorno. A su vez establece las configs predeterminadas de la configuración del framework.
-   4: **Load Config** Une las configuraciones del desarrollador con las predeterminadas, sobre escribiendo las del framework con las establecidas por el usuario.
-   5: **Make Request** (Condicionado a request de la Web) Construye la request entrante. Solo establece ciertos valores, del server entrante.
-   6: **Load Translate files** Carga los archivos de traducción (internos de la app y los usados por el usuario)
-   7.1: **Load Web Routes** Carga las rutas definidas para la web
-   7.2: **Load API Routes** Carga las rutas definidas para la API

#### Lifecycle aplicado en cargas por HTTP (Web y API)

-   8: **Find Route** Encontrar la ruta entrando en la definición.
-   9: **Response Make** Construye el response de la aplicación
-   10: **Carry Through** Si la ruta es encontrada o no, se intentara hacer un render (El 404 es invocado a este punto en caso de ser necesario).
-   11: **Call Action** En caso de existir la ruta se intenta llamar la acción asociada (Callback o Controlador)
-   11.1: **Render Or Return Json** Devuelve una vista o retorna un json
-   12: **Render Debug Bar** Mostrar la barra de debug
-   13: **Response Return** El Response se construye a lo largo de la app, este hace la construcción final para la aplicación
