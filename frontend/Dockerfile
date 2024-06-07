FROM node:18-alpine AS base

FROM base AS deps

RUN apk add --no-cache libc6-compat
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm install

FROM base AS builder
WORKDIR /app
COPY --from=deps /app/node_modules ./node_modules
COPY . .

RUN npm run build

FROM base AS runner
WORKDIR /app

ENV NODE_ENV production

RUN addgroup --system --gid 1001 nodejs
RUN adduser --system --uid 1001 nextjs

COPY --from=builder /app/public ./public

RUN mkdir .next
RUN chown nextjs:nodejs .next

COPY --from=builder ./app/next.config.js ./
COPY --from=builder ./app/public         ./public
COPY --from=builder ./app/.next          ./.next
COPY --from=builder ./app/node_modules   ./node_modules
COPY --from=builder ./app/package.json   ./

EXPOSE 3000

RUN npx next telemetry disable

CMD ["node_modules/.bin/next", "start"]
