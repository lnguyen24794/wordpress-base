# WordPress Docker Development Environment

Môi trường phát triển WordPress sử dụng Docker.

## Thành phần
- WordPress (phiên bản mới nhất)
- MySQL 8.0
- phpMyAdmin

## Yêu cầu
- Docker
- Docker Compose

## Cài đặt

1. Clone repository này về máy của bạn:
```bash
git clone <repository-url>
cd wordpress-base
```

2. Khởi động Docker containers:
```bash
docker-compose up -d
```

3. Truy cập vào các dịch vụ:
   - WordPress: http://localhost:8000
   - phpMyAdmin: http://localhost:8080 (username: root, password: wordpress)

## Cấu trúc thư mục
- `wp-content/`: Thư mục chứa themes, plugins và uploads của WordPress
- `uploads.ini`: Cấu hình PHP để tăng giới hạn upload

## Dừng môi trường

Để dừng các containers:
```bash
docker-compose down
```

Để dừng và xóa volumes (cẩn thận, dữ liệu database sẽ bị mất):
```bash
docker-compose down -v
``` 