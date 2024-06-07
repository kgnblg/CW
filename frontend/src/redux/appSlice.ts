import { createSlice } from "@reduxjs/toolkit";

const initialState: { token: any | null, user: any | null } = {
  token: null,
  user: null,
};

const appSlice = createSlice({
  name: "app",
  initialState: initialState,
  reducers: {
    setToken: (state, action) => {
      state.token = action.payload;
      return state;
    },
    setUser: (state, action) => {
      state.user = action.payload;
      return state;
    },
    clearUser: (state) => {
      state.token = null;
      state.user = {};
      return state;
    },
  },
});

export const { setToken, setUser, clearUser } = appSlice.actions;
export default appSlice.reducer;
