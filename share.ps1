Start-Process php -ArgumentList "-S localhost:8000" -WindowStyle Minimized
Write-Host "Starting Tunnel..."
ssh -o StrictHostKeyChecking=no -R 80:localhost:8000 serveo.net
