# MIM Traders — Kubernetes Deployment

This folder contains all Kubernetes manifests to deploy the MIM Traders PHP/MySQL
e-commerce application on any Kubernetes cluster.

## File Overview

| File | Purpose |
|------|---------|
| `namespace.yaml` | Isolated `mim-traders` namespace |
| `configmap.yaml` | Non-sensitive app config (DB host, port, name) |
| `secret.yaml` | MySQL credentials (base64-encoded) |
| `pvc.yaml` | PersistentVolumeClaims for MySQL data and uploaded images |
| `mysql-init-configmap.yaml` | Instructions for importing `shop_db.sql` schema |
| `mysql-statefulset.yaml` | MySQL 8.0 StatefulSet + headless Service |
| `app-deployment.yaml` | PHP/Apache app Deployment (2 replicas) + ClusterIP Service |
| `ingress.yaml` | Ingress to expose the app on a hostname |
| `hpa.yaml` | HorizontalPodAutoscaler (2–10 replicas, CPU/memory) |
| `Dockerfile` | Build the PHP app image (place in repo root) |

## Prerequisites

- Kubernetes cluster (v1.24+)
- `kubectl` configured
- An Ingress controller installed (e.g. `nginx-ingress`)
- A Docker registry account (Docker Hub, ECR, GCR, etc.)

---

## Step-by-Step Deployment

### 1. Build & Push the Docker Image

```bash
# From the root of the MIM_Traders repository
docker build -t your-dockerhub-username/mim-traders:latest -f kubernetes/Dockerfile .
docker push your-dockerhub-username/mim-traders:latest
```

Update the `image:` field in `app-deployment.yaml` with your image name.

### 2. Set Your Secrets

Edit `secret.yaml` and replace the base64 values with your own credentials:

```bash
echo -n 'your-root-password' | base64
echo -n 'your-db-user'       | base64
echo -n 'your-db-password'   | base64
```

### 3. Import the Database Schema

**Option A — Bake into image (recommended):**
Add this line to the `Dockerfile`:
```dockerfile
COPY shop_db.sql /docker-entrypoint-initdb.d/shop_db.sql
```
The MySQL container will auto-import it on first boot.

**Option B — Manual import after deploy:**
```bash
kubectl exec -n mim-traders -it mysql-0 -- \
  mysql -u root -p shop_db < shop_db.sql
```

### 4. Apply All Manifests

```bash
kubectl apply -f kubernetes/namespace.yaml
kubectl apply -f kubernetes/configmap.yaml
kubectl apply -f kubernetes/secret.yaml
kubectl apply -f kubernetes/pvc.yaml
kubectl apply -f kubernetes/mysql-init-configmap.yaml
kubectl apply -f kubernetes/mysql-statefulset.yaml
kubectl apply -f kubernetes/app-deployment.yaml
kubectl apply -f kubernetes/ingress.yaml
kubectl apply -f kubernetes/hpa.yaml
```

Or apply all at once:
```bash
kubectl apply -f kubernetes/
```

### 5. Verify Deployment

```bash
kubectl get all -n mim-traders
kubectl get ingress -n mim-traders
```

### 6. Access the Application

Update `ingress.yaml` with your real domain, then visit:
- **User Portal:** `http://mimtraders.example.com/home.php`
- **Admin Portal:** `http://mimtraders.example.com/admin/dashboard.php`

---

## Default Login Credentials

| Role  | Username/Email      | Password |
|-------|---------------------|----------|
| User  | user1@gmail.com     | 1234     |
| Admin | admin               | 111      |

> ⚠️ Change these immediately after first login in production!

---

## Architecture

```
Internet → Ingress (nginx) → mim-traders-service (ClusterIP)
                                  ↓
                          mim-traders-app (PHP/Apache Pods × 2–10)
                                  ↓
                          mysql-service (Headless)
                                  ↓
                          mysql-0 (StatefulSet) → mysql-pvc (5Gi)

Uploaded images shared via uploaded-img-pvc (ReadWriteMany)
```
