<?php

$instance = DataBase::getInstance();
$connection = $instance->getConnection();
$competenciasTitle = [
    "Diagnóstico basado en criterios actualizados, investigación de factores psicosociales y tratamiento de trastornos funcionales esofágicos, dispepsia funcional y  Síndrome de Intestino Irritable.", 
    "Conocer el fundamento de la técnica y procedimiento, indicaciones, limitaciones e interpretación de la phmetría y manometría esofágicas.", 
    "Diagnóstico, tratamiento y seguimiento del erge y sus complicaciones.",
    "Diagnosticar y tratar la gastritis, erosiones y enfermedad ulcerosa asociada a <i>H.pylori</i> y AINE´s y la enfermedad ulcerosa péptica refractaria.",
    "Aplicar los algoritmos diagnósticos y tratamiento de la malaabsorción intestinal Síndromes de Malabsorción Intestinal. Enfermedades relacionadas con el gluten.",
    "Aplicar los algoritmos de diagnóstico de los pólipos de colon, clasificación, tratamiento, seguimiento y cribado del CCR.",
    "Seleccionar la mejor estrategia diagnóstica en el estudio del intestino delgado: cápsula endoscópica, enteroscopia, enterografía por CT y por RMN.",
    "Aplicar los algoritmos de diganóstico, diagnóstico diferencial y tratamiento de la colitis ulcerosa y enfermedad de Crohn.",
    "Reconocer las manifestaciones intestinales y extraintestinales de la EII.",
    "Participar en las decisiones terapéuticas (tratamiento de los brotes y mantenimiento de la remisión) incluyendo indicaciones de terapias biológicas.",
    "Competencias en reconocer el síndrome de hepatitis aguda, establecer el diagnóstico diferencial e interpretar la serología de la hepatitis viral para el DPC en digestivo.",
    "Reconocer la aparición de ascitis y su diagnóstico diferencial.",
    "Diagnosticar la aparición de encefalopatía hepática y el diagnóstico diferencial.",
    "Conocer el protocolo de diagnóstico de lesiones focales hepáticas. Reconocer la aparición de hepatocarcinoma en el paciente cirrótico.",
    "Realizar elastometría de transición.",
    "Conocer las indicaciones, contraindicaciones y posibles complicaciones de las exploraciones endoscópicas digestivas. Conocer los consentimientos informados de todas las exploraciones endoscópicas digestivas. Consentimientos informados.",
    "Realizar endoscopia digestiva alta.",
    "Realizar colonoscopia.",
    "Técnicas endoscópicas de inyección, mecánicas, térmicas y otras para hemostasia.",
    "Conocer el manejo de las complicaciones en endoscopia.",
    "Ecografía abdominal: realización.",
    "Ecoendoscopia: indicaciones.",
    "Evaluar los requerimientos de líquidos, electrolitos, macro y micro nutrientes, oligoelementos, en diferentes situaciones clínicas.",
    "Conocer la historia natural de los tumores digestivos y sus posibles agentes causales.",
    "Conocer las condiciones premalignas de los tumores más prevalentes en aparato digestivo.",
    "Indicar e interpretar los resultados de las técnicas más habituales: Anatomía Patológica, Radiodiagnóstico, Endoscopia, Ecoendoscopia en el marco de un equipo multidisciplinar.",
    "Seleccionar e interpretar las pruebas endoscópicas y de imagen para el estudio de la enfermedad de la vesícula y vía biliar.",
    "Identificar y tratar el cólico biliar, la colecistitis aguda, la obstrucción biliar y la colangitis aguda.",
    "Diagnosticar y aplicar los criterios de gravedad (clínicos, metabóicos y radiológicos) y tratamiento de la Pancreatitis Aguda.",
    "Identificar y participar en el tratamiento de las complicaciones locales y sistémicas de la pancreatitis aguda y crónica, e indicaciones de cirugía.",
    "Participar en el diagnóstico de la Insuficiencia Pancreática Exocrina (IPE).",
    "Diagnosticar y participar en el tratamiento de la enfermedad hemorroidal y de la fisura anal.",
    "Aplicar el algoritmo diagnóstico, diagnóstico diferencial, selección de pruebas diagnósticas y actitud terapéutica ante el dolor.",
    "Identificar, evaluar la gravedad, aplicar medidas generales, diagnosticar y tratar la hemorragia digestiva."
];

foreach ($competenciasTitle as $competenciaTitle):
    ob_start();
        $strWithCurrentLength = substr($competenciaTitle, 0, 190);
        echo "<br>» Replacing '{$strWithCurrentLength}' to '{$competenciaTitle}'... ";
        if ($connection->query("UPDATE competencias SET titulo = '{$competenciaTitle}' WHERE titulo LIKE '{$strWithCurrentLength}%'")) echo "<b>DONE!</b>";
        else echo "<b>ERROR! ({$connection->error})</b>";
        echo "<br>";
    ob_end_flush();
    flush();
endforeach;