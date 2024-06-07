import axiosInstance from "@/network/axisInstance";
import * as Routes from "./routes";

export function logIn(loginDTO: any) {
  return axiosInstance.post(Routes.loginApi, loginDTO);
}

export function signUp(signUpDTO: any) {
  return axiosInstance.post(Routes.signUpApi, signUpDTO, {
    headers: {
      "Content-Type": "multipart/form-data",
    },
  });
}

export function getUser() {
  return axiosInstance.get(Routes.userApi);
}
