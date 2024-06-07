import { configureStore } from "@reduxjs/toolkit";
import { TypedUseSelectorHook, useSelector } from "react-redux";
import appSlice from "./redux/appSlice";

export const Store = configureStore({
  reducer: { appReducer: appSlice },
});

export type RootState = ReturnType<typeof Store.getState>;
export const useAppSelector: TypedUseSelectorHook<RootState> = useSelector;
