import axios from "axios";
import getConfig from 'next/config';
const { serverRuntimeConfig, publicRuntimeConfig } = getConfig();
const API_URI = serverRuntimeConfig.apiUrl || publicRuntimeConfig.apiUrl;

const options = {
  baseURL: `${API_URI}/api`,
};

const axiosInstance = axios.create(options);
export default axiosInstance;
