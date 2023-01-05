start:
	docker-compose up -d
	symfony serve -d

stop:
	docker-compose stop
	symfony server:stop
