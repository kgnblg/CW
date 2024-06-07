/* eslint-disable @next/next/no-img-element */
"use client";
import { getUser } from "@/api/userApi";
import { setUser } from "@/redux/appSlice";
import Spinner from "@/components/Spinner";
import { useAppSelector } from "@/store";
import React, { useEffect } from "react";
import { useDispatch } from "react-redux";

const Page = () => {
  const user = useAppSelector<any>((state) => state.appReducer.user);
  const [loading, setLoading] = React.useState(true);
  const dispatch = useDispatch();

  useEffect(() => {
    getUser()
      .then((res) => {
        setLoading(false);
        dispatch(setUser(res.data));
      })
      .catch((err) => {});
  }, []);

  return loading ? (
    <Spinner/>
  ) : (
    <div className="p-4 w-full h-screen dark:bg-gray-800 flex  items-center justify-center">
      <div className=" p-5 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-10 max-w-2xl border-solid border-2 border-sky-500">
        <div className="flex justify-center items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
          <div>
            <img
              className="rounded-lg"
              src={
                user?.avatar ||
                "https://ps.w.org/user-avatar-reloaded/assets/icon-128x128.png?rev=2540745"
              }
              alt=""
              width={50}
              height={50}
            />
          </div>
          <h3 className="text-lg font-semibold text-gray-900 dark:text-white ml-5">
            {user?.fullName}
          </h3>
        </div>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 m:1">
          {user?.photos?.map((photo: any) => (
            <div key={photo.id}>
              <img
                className="h-auto max-w-full rounded-lg"
                src={photo.url}
                alt={photo.name}
                width={50}
                height={50}
              />
            </div>
          ))}
        </div>
        <form action="#">
          <div className="grid gap-4 mb-4 sm:grid-cols-2 mt-10">
            <div>
              <label
                htmlFor="name"
                className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Name
              </label>
              <input
                type="text"
                name="name"
                id="name"
                value={user?.firstName}
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Ex. Apple iMac 27&ldquo;"
              />
            </div>
            <div>
              <label
                htmlFor="Last Name"
                className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Last Name
              </label>
              <input
                type="text"
                name="Last Name"
                id="Last Name"
                value={user?.lastName}
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Ex. Apple"
              />
            </div>
            <div>
              <label
                htmlFor="email"
                className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                Email
              </label>
              <input
                type="email"
                value={user?.email}
                name="email"
                id="email"
                className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
              />
            </div>
            <div />
          </div>
        </form>
      </div>
    </div>
  );
};

export default Page;
