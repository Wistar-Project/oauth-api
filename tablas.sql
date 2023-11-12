USE gotruck;

CREATE TABLE personas(
    id bigint unsigned primary key,
    nombre varchar(50) not null,
    apellido varchar(50) not null,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime, -- por laravel
	foreign key (id) references users (id) -- la tabla de users sale de laravel
);

CREATE TABLE gerentes(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references personas(id)
);

CREATE TABLE administradores(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references personas(id)
);

CREATE TABLE conductores(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references personas(id)
);

CREATE TABLE funcionarios(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references personas(id)
);

CREATE VIEW personas_roles AS
    SELECT id, "gerente" rol from gerentes
    UNION
    SELECT id, "administrador" rol from administradores
    UNION
    SELECT id, "funcionario" rol from funcionarios
    UNION
    SELECT id, "conductor" rol from conductores;
    
CREATE TABLE alojamientos(
    id serial primary key,
    direccion varchar(100) unique not null,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime-- por laravel
);

CREATE TABLE almacenes(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references alojamientos(id)
);

CREATE TABLE sedes(
    id bigint unsigned primary key,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id) references alojamientos(id)
);

CREATE VIEW alojamientos_tipos AS
    SELECT id, "sede" tipo from sedes
    UNION
    SELECT id, "almacén" tipo from almacenes;
    
CREATE TABLE paquetes(
    id serial primary key,
    peso_en_kg smallint not null,
    email varchar(70) not null,
    destino bigint unsigned not null,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (destino) references sedes(id)
);

CREATE TABLE lotes(
    id serial primary key,
    destino bigint unsigned not null,
    updated_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (destino) references sedes(id)
);

CREATE TABLE lote_formado_por(
	id_paquete bigint unsigned primary key,
    id_lote bigint unsigned not null,
    updated_at datetime,-- por laravel
    created_at datetime,-- por laravel
    foreign key (id_lote) references lotes(id),
    foreign key (id_paquete) references paquetes(id)
);

CREATE TABLE vehiculos(
	id serial primary key,
	capacidad_en_toneladas smallint not null,
	updated_at datetime,-- por laravel
    created_at datetime,-- por laravel
    deleted_at datetime-- por laravel
);

CREATE TABLE camiones(
	id_vehiculo bigint unsigned primary key,
	foreign key (id_vehiculo) references vehiculos(id)
);

CREATE TABLE conductor_maneja(
	id_conductor bigint unsigned primary key,
    id_vehiculo bigint unsigned not null unique,
    foreign key (id_vehiculo) references vehiculos(id),
    foreign key (id_conductor) references conductores(id)
);

CREATE TABLE pickups(
	id_vehiculo bigint unsigned primary key,
	foreign key (id_vehiculo) references vehiculos(id)
);

CREATE TABLE lote_asignado_a_camion(
	id_lote bigint unsigned primary key,
    id_camion bigint unsigned not null,
	updated_at datetime,-- por laravel
    created_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    foreign key (id_lote) references lotes(id),
    foreign key (id_camion) references camiones(id_vehiculo)
);

CREATE TABLE paquete_asignado_a_pickup(
	id_paquete bigint unsigned primary key,
    id_pickup bigint unsigned not null,
	updated_at datetime,-- por laravel
    created_at datetime,-- por laravel
    deleted_at datetime,-- por laravel
    foreign key (id_paquete) references paquetes(id),
    foreign key (id_pickup) references pickups(id_vehiculo)
);

CREATE TABLE traducciones(
	id bigint,
    texto varchar(1000) not null,
    idioma enum('es', 'en'),
    primary key (id, idioma)
);

CREATE VIEW vehiculos_tipos AS
    SELECT id_vehiculo, "camión" tipo from camiones
    UNION
    SELECT id_vehiculo, "pickup" tipo from pickups;