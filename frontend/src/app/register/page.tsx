/* eslint-disable @next/next/no-img-element */
"use client";
import { signUp } from "@/api/userApi";
import Spinner from "@/components/Spinner";
import { useRouter } from "next/navigation";
import { useState } from "react";
import { useForm } from "react-hook-form";

export default function Home() {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm();

  const [loading, setLoading] = useState(false);
  const router = useRouter();
  const [errorMessages, setErrorMessages] = useState("");

  const onSubmit = (data: any) => {
    setLoading(true);
    var bodyFormData = new FormData();
    bodyFormData.append("email", data.email);
    bodyFormData.append("password", data.password);
    bodyFormData.append("firstname", data.firstname);
    bodyFormData.append("lastname", data.lastname);
    bodyFormData.append("avatar", data.avatar?.[0]);

    for (const key in data?.photos) {
      bodyFormData.append(`photos[${key}]`, data?.photos[key]);
    }

    setErrorMessages("");
    signUp(bodyFormData)
      .then((res) => {
        if (res.data.status === "successful") {
          setLoading(false);
          window.alert("User created successfully");
          router.push("/");
        } else {
          setLoading(false);
          setErrorMessages(res.data.message);
        }
      })
      .catch((err) => {
        setLoading(false);

        setErrorMessages(err?.response?.data?.message);
      });
  };

  return loading ? (
    <Spinner/>
  ) : (
    <section className="bg-gray-50 dark:bg-gray-900">
      <div className="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <a
          href="#"
          className="flex items-center mb-6 text-2xl font-semibold text-gray-900 mt-10 dark:text-white"
        >
          <img
            className="w-8 h-8 mr-2"
            src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
            alt="logo"
          />
          CW
        </a>
        <div className="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div className="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 className="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
              Register
            </h1>
            <form
              className="space-y-4 md:space-y-6"
              action="#"
              onSubmit={handleSubmit(onSubmit)}
            >
              <label
                className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                htmlFor="file_input"
              >
                Chose Avatar
              </label>
              <input
                {...register("avatar")}
                className="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                id="file_input"
                type="file"
              />

              <div>
                <label
                  htmlFor="email"
                  className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  Your email
                </label>
                <input
                  {...register("email", { required: true })}
                  type="email"
                  name="email"
                  id="email"
                  className="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="name@company.com"
                  required
                />
                {errors.email && (
                  <p className="text-red-500 mt-3">Email is required</p>
                )}
              </div>

              <div>
                <label
                  htmlFor="firstname"
                  className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  FirstName
                </label>
                <input
                  {...register("firstname", {
                    required: true,
                    minLength: 2,
                    maxLength: 25,
                  })}
                  type="text"
                  name="firstname"
                  id="firstname"
                  className="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="firstName"
                  required
                />
                {errors.firstname && (
                  <p className="text-red-500 mt-3">
                    Firstname is required , min 2 and max25 characters
                  </p>
                )}
              </div>

              <div>
                <label
                  htmlFor="lastname"
                  className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  LastName
                </label>
                <input
                  {...register("lastname", {
                    required: true,
                    minLength: 2,
                    maxLength: 25,
                  })}
                  type="text"
                  name="lastname"
                  id="lastname"
                  className="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="lastname"
                  required
                />

                {errors.lastname && (
                  <p className="text-red-500 mt-3">
                    Lastname is required , min 2 and max25 characters
                  </p>
                )}
              </div>

              <div>
                <label
                  htmlFor="password"
                  className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  Password
                </label>
                <input
                  {...register("password", {
                    required: true,
                    minLength: 6,
                    maxLength: 50,
                    pattern: /\d/,
                  })}
                  type="password"
                  name="password"
                  id="password"
                  placeholder="••••••••"
                  className="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  required
                />
                {errors.password && (
                  <p className="text-red-500 mt-3">
                    Password must be 6 characters long and contain at least 1
                    digit
                  </p>
                )}
              </div>

              <label
                className="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                htmlFor="multiple_files"
              >
                Upload multiple photos
              </label>
              <input
                {...register("photos", {
                  required: true,
                  minLength: 4,
                })}
                className="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                id="multiple_files"
                type="file"
                multiple
              />
              {errors.photos && (
                <p className="text-red-500 mt-3">Please upload 4 photos</p>
              )}
              <button
                type="submit"
                className="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
              >
                Create an account
              </button>

              {errorMessages !== "" && (
                <p className="text-red-500 mt-5">{errorMessages}</p>
              )}

              <p className="text-sm font-light text-gray-500 dark:text-gray-400">
                Already have an account?{" "}
                <a
                  href="/"
                  className="font-medium text-primary-600 hover:underline dark:text-primary-500"
                >
                  Login here
                </a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </section>
  );
}
