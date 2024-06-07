/** @type {import('next').NextConfig} */
const nextConfig = {
  serverRuntimeConfig: {
    // Will only be available on the server side
    URI: 'nginx:8000'
  },
  publicRuntimeConfig: {
    // Will be available on both server and client
    URI: 'http://localhost:8000'
  },
  output: 'standalone',
  eslint: {
    ignoreDuringBuilds: true,
  },
};

module.exports = nextConfig;
