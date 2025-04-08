import axios from 'axios';

/**
 *  Adaptador para realizar peticiones HTTP
 ** Implementación actual: AXIOS
 */
export class Http {
    /**
     * Realiza una petición GET.
     * @param {string} url      - URL de la solicitud.
     * @param {object} params   - Parámetros opcionales de la URL.
     * @param {object} headers  - Encabezados opcionales.
     * @returns {Promise}
     */
    static async get(url, params = {}, headers = {}) {
        try {
            // -------- Implementación --------
            return (
                await axios.get(url, {
                    params,
                    headers
                })
            ).data;
            // --------------------------------
        } catch (error) {
            return Http.capError(error);
        }
    }

    /**
     * Realiza una petición POST.
     * @param {string} url      - URL de la solicitud.
     * @param {object} data     - Datos a enviar en el cuerpo de la solicitud.
     * @param {object} headers  - Encabezados opcionales.
     * @returns {Promise}
     */
    static async post(url, data = {}, headers = {}) {
        try {
            // -------- Implementación --------
            return await axios.post(url, data, { headers });
            // --------------------------------
        } catch (error) {
            return Http.capError(error);
        }
    }

    /**
     * Manejo de errores.
     * @param {object} error    - Error devuelto por Axios.
     * @returns {object}        - Un objeto con el mensaje de error y código de estado.
     */
    static capError(error) {
        
        if (error.response) {

            const { status, data } = error.response;
            console.error("Error en la respuesta:", status, data);

            return { 
                status, 
                message: data, 
            };

        } else if (error.request) {

            console.error("Error en la solicitud:", error.request);

            return { 
                status: 500,
                message: "No se recibió respuesta del servidor." 
            };

        } else {

            console.error("Error inesperado:", error.message);
            return { 
                status: 500,
                message: "Error interno en la petición." 
            };
        }
    }
}

// Ejemplo
const getData = async () => {
    let response = await Http.get('https://jsonplaceholder.typicode.com/todos/1');
    console.log(
        response
    );
};