# Usa a imagem oficial do Nginx como base
FROM nginx

# Copia o arquivo de configuração personalizado do Nginx para o container
COPY nginx.conf /etc/nginx/nginx.conf
