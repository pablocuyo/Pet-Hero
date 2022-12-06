create database pethero;
use pethero;

create table if not exists Usuarios(

id_usuario bigint auto_increment,
username varchar(50) not null,
dni varchar(50) not null,
nombre varchar(50) not null,
apellido varchar(50) not null,
correo varchar(50) not null,
password varchar(50) not null,
telefono varchar(50) not null,
direccion varchar(50) not null,
foto_perfil varchar(50),
tipo_usuario varchar(1) not null,

constraint pk_usuario primary key (id_usuario),
constraint unq_username unique (username),
constraint unq_dni unique (dni),
constraint unq_correo unique (correo)
);


create table if not exists Guardianes(

id_guardian bigint auto_increment,
id_usuario bigint not null,
dia_inicio date not null,
dia_fin date not null,
descripcion varchar(150) not null,
costo_diario float not null,
foto_espacio varchar(50) not null,

constraint pk_guardian primary key (id_guardian),
constraint fk_guardian_usuario foreign key (id_usuario) references usuarios(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE,
constraint unq_id_usuario unique (id_usuario),
constraint chk_costo_diario check (costo_diario >=0)

);

create table if not exists Dueños(

id_dueño bigint auto_increment,
id_usuario bigint not null,

constraint pk_dueño primary key (id_dueño),
constraint fk_dueño_usuario foreign key (id_usuario) references usuarios(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE,
constraint unq_id_usuario unique (id_usuario)
);

create table if not exists Favoritos(

id_favorito bigint auto_increment,
id_dueño bigint not null,
id_guardianFav bigint not null,
activo bool default true,

constraint fk_favorito_dueño foreign key (id_dueño) references dueños(id_dueño) ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_favorito_guardianFav foreign key (id_guardianFav) references guardianes(id_guardian) ON UPDATE CASCADE ON DELETE CASCADE,
constraint pk_favoritos primary key (id_favorito),
constraint unq_dueño_guardianFav unique (id_dueño, id_guardianFav)
);

create table if not exists Tamaños(

id_tamaño bigint auto_increment,
nombre_tamaño varchar(50) not null,
activo bool default true,

constraint pk_tamaño primary key (id_tamaño),
constraint unq_nombre_tamaño unique (nombre_tamaño)
);

create table if not exists Tamaños_x_Guardianes(

id_tamaño_x_guardian bigint auto_increment,
id_tamaño bigint not null,
id_guardian bigint not null,
activo boolean default true not null,

constraint pk_tamaño_x_guardian primary key (id_tamaño_x_guardian),
constraint fk_tam_x_guar_tamaño foreign key (id_tamaño) references tamaños(id_tamaño) ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_tam_x_guar_guardian foreign key (id_guardian) references guardianes(id_guardian) ON UPDATE CASCADE ON DELETE CASCADE
);

create table if not exists Especies(

id_especie bigint auto_increment,
nombre_especie varchar(50) not null,

constraint pk_especie primary key (id_especie),
constraint unq_nombre_especie unique (nombre_especie)
);

create table if not exists Razas(

id_raza bigint auto_increment,
nombre_raza varchar(50) not null,
id_especie bigint not null,

constraint pk_raza primary key (id_raza),
constraint fk_raza_especie foreign key (id_especie) references especies(id_especie) ON UPDATE CASCADE ON DELETE CASCADE
);

create table if not exists Mascotas(

id_mascota bigint auto_increment,
nombre varchar(50) not null,
id_raza bigint not null,
id_tamaño bigint  not null,
id_dueño bigint not null,
plan_vacunacion varchar(50) not null,
foto_mascota varchar(50) not null,
video varchar(50),

constraint pk_mascota primary key (id_mascota),
constraint fk_mascota_raza foreign key (id_raza) references razas (id_raza) ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_mascota_tamaño foreign key (id_tamaño) references tamaños (id_tamaño) ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_mascota_dueño foreign key (id_dueño) references dueños (id_dueño) ON UPDATE CASCADE ON DELETE CASCADE

);


create table if not exists Reservas(

id_reserva bigint auto_increment,
fecha_reserva date not null,
fecha_inicio date not null,
fecha_fin date not null,
id_guardian bigint not null,
id_dueño bigint not null,
id_mascota bigint not null,
costo_total float,
estado varchar(50),

constraint pk_reserva primary key (id_reserva),
constraint fk_reserva_guardian foreign key (id_guardian) references usuarios(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_reserva_dueño foreign key (id_dueño) references usuarios (id_usuario) ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_reserva_mascota foreign key (id_mascota) references mascotas(id_mascota) ON UPDATE CASCADE ON DELETE CASCADE,
constraint chk_costo_total check (costo_total >=0)
);

create table if not exists Reviews(

id_review bigint auto_increment,
fecha date not null,
id_dueño bigint not null,
id_guardian bigint not null,
id_reserva bigint not null,
calificacion float not null,
comentario varchar(150),

constraint pk_review primary key (id_review),
constraint fk_review_dueño foreign key (id_dueño) references usuarios(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_review_guardian foreign key (id_guardian) references usuarios(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_review_reserva foreign key (id_reserva) references reservas(id_reserva) ON UPDATE CASCADE ON DELETE CASCADE
);

create table if not exists Mensajes(
      
id_mensaje bigint  AUTO_INCREMENT,
fecha datetime not null ,
id_emisor bigint NOT NULL,
id_receptor bigint NOT NULL,
contenido varchar(150) not null,

constraint pk_id_mensaje PRIMARY KEY (id_mensaje),
constraint fk_id_emisor FOREIGN KEY (id_emisor) REFERENCES usuarios (id_usuario)  ON UPDATE CASCADE ON DELETE CASCADE,
constraint fk_id_receptor FOREIGN KEY (id_receptor) REFERENCES usuarios (id_usuario)  ON UPDATE CASCADE ON DELETE CASCADE
);

create table if not exists tokens(

token varchar(15),
id_reserva bigint not null,

constraint pk_token primary key (token),
constraint fk_token_reserva foreign key (id_reserva) references reservas(id_reserva) ON UPDATE CASCADE ON DELETE CASCADE

);



/*TAMAÑOS*/

insert into tamaños (nombre_tamaño) values ("Grande");
insert into tamaños (nombre_tamaño) values ("Mediano");
insert into tamaños (nombre_tamaño) values ("Pequeño");

/*ESPECIES*/

insert into especies (nombre_especie) values ("Perro");
insert into especies (nombre_especie) values ("Gato");

/*RAZAS PERROS*/
insert into razas(nombre_raza, id_especie) values ("Braco alemán", 1);
insert into razas(nombre_raza, id_especie) values ("Labrador retriever", 1);
insert into razas(nombre_raza, id_especie) values ("Bulldog francés", 1);
insert into razas(nombre_raza, id_especie) values ("Golden retriever", 1);
insert into razas(nombre_raza, id_especie) values ("Pastor alemán", 1);
insert into razas(nombre_raza, id_especie) values ("Bulldog inglés", 1);
insert into razas(nombre_raza, id_especie) values ("Beagle", 1);
insert into razas(nombre_raza, id_especie) values ("Rottweiler", 1);
insert into razas(nombre_raza, id_especie) values ("Dachshund (Mini Salchicha)", 1);
insert into razas(nombre_raza, id_especie) values ("Pug", 1);
insert into razas(nombre_raza, id_especie) values ("Sin raza", 1);
insert into razas(nombre_raza, id_especie) values ("Cocker", 1);
insert into razas(nombre_raza, id_especie) values ("Yorkshire terrier", 1);
insert into razas(nombre_raza, id_especie) values ("Boxer", 1);
insert into razas(nombre_raza, id_especie) values ("Pinsher mini", 1);
insert into razas(nombre_raza, id_especie) values ("Terrier Tibetano", 1);
insert into razas(nombre_raza, id_especie) values ("Siberianor", 1);
insert into razas(nombre_raza, id_especie) values ("Shiba inu", 1);
insert into razas(nombre_raza, id_especie) values ("Chow Chow", 1);
insert into razas(nombre_raza, id_especie) values ("Sharpei", 1);
insert into razas(nombre_raza, id_especie) values ("Basset Hound (Batata)", 1);
insert into razas(nombre_raza, id_especie) values ("Caniche", 1);
insert into razas(nombre_raza, id_especie) values ("Chihuahua", 1);
insert into razas(nombre_raza, id_especie) values ("Corgi", 1);
insert into razas(nombre_raza, id_especie) values ("Cocker", 1);
insert into razas(nombre_raza, id_especie) values ("Schanuzer", 1);
insert into razas(nombre_raza, id_especie) values ("Breton", 1);
insert into razas(nombre_raza, id_especie) values ("Collie", 1);
insert into razas(nombre_raza, id_especie) values ("Gran Danés", 1);
insert into razas(nombre_raza, id_especie) values ("San bernardo", 1);
insert into razas(nombre_raza, id_especie) values ("Terranova", 1);
insert into razas(nombre_raza, id_especie) values ("Dalmata", 1);
insert into razas(nombre_raza, id_especie) values ("Doberman", 1);
insert into razas(nombre_raza, id_especie) values ("Galgo", 1);
insert into razas(nombre_raza, id_especie) values ("Bull Terrier", 1);

/*RAZAS GATOS*/
insert into razas(nombre_raza, id_especie) values ("Persa", 2);
insert into razas(nombre_raza, id_especie) values ("Azul ruso", 2);
insert into razas(nombre_raza, id_especie) values ("Siamés", 2);
insert into razas(nombre_raza, id_especie) values ("Angora turco", 2);
insert into razas(nombre_raza, id_especie) values ("Siberiano", 2);
insert into razas(nombre_raza, id_especie) values ("Maine Coon", 2);
insert into razas(nombre_raza, id_especie) values ("Bengalí", 2);


DELIMITER //
create procedure agregar_favorito(in p_id_dueño bigint, p_id_guardian bigint)
begin 
declare id_dueño bigint;
declare id_guardian bigint;
set id_dueño=(select d.id_dueño from dueños d inner join usuarios u on d.id_usuario=u.id_usuario where u.id_usuario = p_id_dueño);
set id_guardian=(select g.id_guardian from guardianes g inner join usuarios u on g.id_usuario=u.id_usuario where u.id_usuario = p_id_guardian);
insert into favoritos(id_dueño,id_guardianFav)values(id_dueño,id_guardian);
end//

DELIMITER //
create procedure borrar_favorito(in p_id_dueño bigint, p_id_guardian bigint)
begin 
declare id_dueño_borrar bigint;
declare id_guardian_borrar bigint;
set id_dueño_borrar=(select d.id_dueño from dueños d inner join usuarios u on d.id_usuario=u.id_usuario where u.id_usuario = p_id_dueño);
set id_guardian_borrar=(select g.id_guardian from guardianes g inner join usuarios u on g.id_usuario=u.id_usuario where u.id_usuario = p_id_guardian);
delete from favoritos  where id_dueño=id_dueño_borrar and id_guardianFav=id_guardian_borrar;
end//

delimiter //
create procedure agregar_mascota(in p_nombre varchar(50), in p_raza varchar(50), in p_tamaño varchar(50), in p_id_usuario bigint, in p_plan_vacunacion varchar(100), in p_foto_mascota varchar(100), in p_video varchar(100))
BEGIN
declare id_raza bigint;
declare id_tamaño bigint;
declare id_dueño bigint;
set id_raza = (select r.id_raza from razas r where r.nombre_raza = p_raza);
set id_tamaño = (select t.id_tamaño from tamaños t where t.nombre_tamaño = p_tamaño);
set id_dueño = (select d.id_dueño from dueños d inner join usuarios u on u.id_usuario = d.id_usuario where u.id_usuario = p_id_usuario);

insert into mascotas (nombre, id_raza, id_tamaño, id_dueño, plan_vacunacion, foto_mascota, video) values (p_nombre, id_raza, id_tamaño, id_dueño, p_plan_vacunacion, p_foto_mascota, p_video);



DELIMITER //
create procedure listar_chat(in id_sesion bigint,in id_interlocutor bigint)
begin
SELECT 
      fecha, 
      id_emisor,
      id_receptor,
      contenido 
      from mensajes 
      where (id_emisor =id_sesion and id_receptor =id_interlocutor) or (id_emisor = id_interlocutor and id_receptor = id_sesion)
      order by fecha desc;
END //

delimiter //
create procedure nuevo_mensaje(in emisor bigint, in receptor bigint, in contenido varchar(150) )
BEGIN
insert into mensajes(fecha,id_emisor,id_receptor,contenido)values(current_timestamp(),emisor,receptor, contenido);
END//

DELIMITER //
create procedure listar_solicitud_reservas(in p_estado varchar(50), in id_user_guardian bigint)
begin
SELECT
r.id_reserva,
r.fecha_reserva,
r.fecha_inicio,
r.fecha_fin,
r.id_guardian,
u.username as dueño,
m.nombre as mascota,
r.costo_total,
r.estado 
from 
reservas r
inner join usuarios u on r.id_dueño = u.id_usuario
inner join mascotas m on r.id_mascota = m.id_mascota
where r.id_guardian = id_user_guardian
and r.estado = p_estado;
END //

delimiter //
create procedure nuevo_mensaje(in emisor bigint, in receptor bigint, in contenido varchar(150) )
BEGIN
insert into mensajes(fecha,id_emisor,id_receptor,contenido)values(current_timestamp(),emisor,receptor, contenido);
END//

DELIMITER //
create procedure listar_chat(in id_sesion bigint,in id_interlocutor bigint)
begin
SELECT 
      fecha, 
      id_emisor,
      id_receptor,
      contenido 
      from mensajes 
      where (id_emisor =id_sesion and id_receptor =id_interlocutor) or (id_emisor = id_interlocutor and id_receptor = id_sesion)
      order by fecha desc;
END //

