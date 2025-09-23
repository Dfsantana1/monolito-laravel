FROM kong:3.4

# Copy Kong configuration
COPY kong.yml /usr/local/kong/declarative/kong.yml

# Set Kong environment variables
ENV KONG_DATABASE=off \
    KONG_DECLARATIVE_CONFIG=/usr/local/kong/declarative/kong.yml \
    KONG_PROXY_ACCESS_LOG=/dev/stdout \
    KONG_ADMIN_ACCESS_LOG=/dev/stdout \
    KONG_PROXY_ERROR_LOG=/dev/stderr \
    KONG_ADMIN_ERROR_LOG=/dev/stderr \
    KONG_ADMIN_LISTEN=0.0.0.0:8001

# Verify configuration
RUN kong config -c /usr/local/kong/declarative/kong.yml

# Expose ports
EXPOSE 8000 8001

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
  CMD kong health || exit 1

# Start Kong
CMD ["kong", "docker-start"]
