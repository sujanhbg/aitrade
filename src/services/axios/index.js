import axios from "axios";
import {getToken} from "@/utilities/token";
import {API_BASE_URL} from "@/services/enpoints";

const instance = axios.create({
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

instance.defaults.timeout = 10000;
instance.defaults.baseURL = API_BASE_URL;
instance.interceptors.request.use(
    async (config) => {
        const token = getToken()
        if (getToken()) {
            config.headers.Authorization = `Bearer ${token}`
            return config;
        }

        return config;

    },

    (error) => {
        return Promise.reject(error);
    },
);

instance.interceptors.response.use(function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
}, function (error) {
    return Promise.reject(error);

});

export default instance;
