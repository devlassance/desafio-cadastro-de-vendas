# Node image for Vue dev server
FROM node:20-alpine

WORKDIR /usr/src/app

# Set safe defaults
ENV CI=true
ENV HOST=0.0.0.0

# Install dependencies if package.json exists (build arg to cache)
RUN apk add --no-cache bash
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 5173
CMD ["/usr/local/bin/entrypoint.sh"]
