

/*///////POBLACION INDIGENA/////////////// */
INSERT INTO reglas_clasificacion (pregunta_id, operador, valor, grupo, descripcion)
VALUES 
(10384, '=', 'Si', 'Poblacion Indígena', 'Tus papás o tus abuelos  son indígenas)'),
(10385, '=', 'Si', 'Poblacion Indígena', 'Tus papás o tus abuelos hablan o entienden alguna lengua indígena)'),
(10386, '=', 'Si', 'Poblacion Indígena', 'Tú hablas  o entiendes alguna lengua indígena:)');

/*//////////END POBLACION INDIGENA///////////// */
/*/////////////////ECONOMICO/////////////////// */
INSERT INTO reglas_clasificacion (pregunta_id, operador, valor, grupo, descripcion)
VALUES
(10301, '=', 'Si', 'Económico', 'Económicamente alguien depende de ti'),
(10339, '=', 'Si', 'Económico', '¿Trabajas?'),
(10363, '<=', '6223.20', 'Económico', 'Ingreso Mensual de todos los integrantes de la familia'),
(10371, '=', 'Pensión (renta cerca de la UTVT)', 'Económico', '¿Cuál es tú lugar de residencia mientras estudias en la carrera?'),
(10375, 'IN', 'De 60 a 90 minutos (1 hr a 1 ½ hrs),De 90 a 120 minutos (1 ½ hrs a 2 hrs),Más de 120 minutos (Más de 2 hrs)', 'Económico', '¿Cuánto tiempo haces diariamente para trasladarte de tu lugar de residencia a la escuela?'),
(10420, '=', 'SI', 'Económico', 'Cursas alguna otra carrera (actualmente):'),

/*///////////END ECONOMICO//////////// */


/*/////////////////////SALUD//////////////////////// */



INSERT INTO reglas_clasificacion (pregunta_id, operador, valor, grupo, descripcion)
VALUES
(10387, 'IN', 'Tienes alguna deficiencia auditiva,Problema de movilidad motriz,Otro:', 'Salud', 'Condiciones de salud (lentes, problmeas notices , etc)'),
(10388, '=', 'Si', 'Salud', 'Respuesta Si a Padecimiento cronico'),
(10390, '=', 'Si', 'Salud', 'Respuesta Si a Tienes alguna alergia?'),
(10392, '=', 'Si', 'Salud', 'Respueta Tomas algun medicamento periodicamente?');
(10394, '=', 'Si', 'Salud', 'Respuesta Si a ¿Has recibido atención psicológica o psiquiátrica?'),


/*///////////////////END SALUD//////////////////////// */


/*//////////////////PATERNAL/////////////////////////// */
INSERT INTO reglas_clasificacion (pregunta_id, operador, valor, grupo, descripcion)
VALUES (10299, 'IN', 'Divorciad(a),Viudo(a),Unión libre,Casado(a)', 'Paternal', 'Estado civil ');


/*//////////////////END PATERNAL/////////////////////////// */


/*//////////////////////////INSERTAR REGISTROS Q CUNPLAN LAS REGLAS////////////////////////////////////// */
INSERT INTO clasificacion_estudiantes (estudiante_id, grupo, pregunta_id, respuesta)
SELECT 
    r.estudiante_id, 
    rc.grupo, 
    r.pregunta_id, 
    r.respuesta
FROM respuestas r
JOIN reglas_clasificacion rc ON r.pregunta_id = rc.pregunta_id
WHERE (
    (rc.operador = '='    AND r.respuesta = rc.valor)
    OR (rc.operador = '!=' AND r.respuesta <> rc.valor)
    OR (rc.operador = '<=' AND r.respuesta <= rc.valor)
    OR (rc.operador = '>'  AND r.respuesta >  rc.valor)
    OR (rc.operador = 'IN' AND FIND_IN_SET(r.respuesta, rc.valor) > 0)
    OR (rc.operador = 'NOT IN' AND FIND_IN_SET(r.respuesta, rc.valor) = 0)
);
