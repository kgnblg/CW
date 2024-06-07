"use client";

import { useCallback, useEffect, useState } from "react";
import { usePathname, useRouter } from "next/navigation";
import { useDispatch } from "react-redux";
import { useAppSelector } from "@/store";
import { setToken } from "@/redux/appSlice";
import { getCookie } from "cookies-next";
import axiosInstance from "@/network/axisInstance";

export default function AuthProvider({ children }: any) {
  const token = useAppSelector((s) => s.appReducer.token);

  const router = useRouter();

  const pathname = usePathname();

  const dispatch = useDispatch();

  const [loading, setLoading] = useState(true);

  const checkAuth = useCallback(() => {
    const cookieToken = getCookie("token");
    axiosInstance.defaults.headers.common[
      "Authorization"
    ] = `Bearer ${cookieToken}`;
    if (pathname === "/") {
      setLoading(false);
    } else if (token === undefined) {
      if (cookieToken) {
        dispatch(setToken(cookieToken));
      } else {
        router.push("/login");
      }

      setLoading(false);
    } else {
      setLoading(false);
    }
  }, [dispatch, pathname, router, token]);

  useEffect(() => {
    checkAuth();
  }, [checkAuth]);

  if (loading) return null;

  return children;
}
