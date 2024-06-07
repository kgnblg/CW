"use client";

import React from "react";
import { Store } from "@/store";
import { Provider } from "react-redux";

export const ReduxProvider = ({ children }: any) => {
  return <Provider store={Store}>{children}</Provider>;
};
