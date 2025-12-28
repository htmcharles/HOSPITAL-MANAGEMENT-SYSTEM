DROP TABLE IF EXISTS `accountants`;

CREATE TABLE IF NOT EXISTS `accountants` (
  `id_u` int(11) NOT NULL,
  `codeaccount` varchar(255) NOT NULL,
  `dateaffectationaccount` date NOT NULL,
  `createdtimeAcc` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyAcc` int(11) NOT NULL,
  PRIMARY KEY (`id_u`),
  KEY `codeaccount` (`codeaccount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `assurances`;

CREATE TABLE IF NOT EXISTS `assurances` (
  `id_assurance` int(11) NOT NULL AUTO_INCREMENT,
  `nomassurance` varchar(255) NOT NULL,
  PRIMARY KEY (`id_assurance`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `auditors`;

CREATE TABLE IF NOT EXISTS `auditors` (
  `id_u` int(11) NOT NULL,
  `codeaudit` varchar(255) NOT NULL,
  `dateaffectationaudit` date NOT NULL,
  `createdtimeAudit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyAudit` int(11) NOT NULL,
  PRIMARY KEY (`id_u`),
  KEY `codeaudit` (`codeaudit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bills`;

CREATE TABLE IF NOT EXISTS `bills` (
  `id_bill` int(11) NOT NULL AUTO_INCREMENT,
  `numbill` varchar(255) NOT NULL,
  `datebill` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `totaltypeconsuprice` decimal(11,2) NOT NULL,
  `totalmedconsuprice` decimal(11,2) NOT NULL,
  `totalmedinfprice` decimal(11,2) NOT NULL,
  `totalmedlaboprice` decimal(11,2) NOT NULL,
  `totalmedconsomprice` decimal(11,2) NOT NULL,
  `totalmedmedocprice` decimal(11,2) NOT NULL,
  `totalgnlprice` decimal(11,2) NOT NULL,
  `dateconsu` date NOT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `idorgBill` int(11) DEFAULT NULL,
  `nomassurance` varchar(255) NOT NULL,
  `idcardbill` varchar(255) NOT NULL,
  `numpolicebill` varchar(255) NOT NULL,
  `adherentbill` varchar(255) NOT NULL,
  `billpercent` int(11) NOT NULL,
  `codecashier` varchar(255) DEFAULT NULL,
  `other_cashier` tinyint(1) NOT NULL,
  `codecoordi` varchar(255) DEFAULT NULL,
  `vouchernum` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `codeaccount` varchar(255) DEFAULT NULL,
  `datedone` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dettecodecashier` varchar(255) DEFAULT NULL,
  `dettecodecashier_other` tinyint(1) NOT NULL,
  `dette` decimal(11,2) DEFAULT NULL,
  `detteIdIn` int(11) DEFAULT NULL,
  `amountpaid` decimal(11,2) DEFAULT NULL,
  `detteDone` int(11) DEFAULT NULL,
  `detteIdOut` int(11) DEFAULT NULL,
  `dateDetteOut` date DEFAULT NULL,
  PRIMARY KEY (`id_bill`),
  KEY `numero` (`numero`),
  KEY `codeaccount` (`codeaccount`),
  KEY `datebill` (`datebill`)
) ENGINE=InnoDB AUTO_INCREMENT=1240 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bills_other_cashier`;

CREATE TABLE IF NOT EXISTS `bills_other_cashier` (
  `id_bill_other_cashier` int(11) NOT NULL AUTO_INCREMENT,
  `id_bill_OG` int(11) NOT NULL,
  `numbill_OG` varchar(255) NOT NULL,
  `datebill_other` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `codecashier_other` varchar(255) NOT NULL,
  PRIMARY KEY (`id_bill_other_cashier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bn_table`;

CREATE TABLE IF NOT EXISTS `bn_table` (
  `bn_type` varchar(255) NOT NULL,
  `bn_y` int(11) NOT NULL,
  `bn_n_i` int(11) NOT NULL,
  `bn_l` varchar(255) NOT NULL,
  `bn_n` int(11) NOT NULL,
  UNIQUE KEY `bn_type` (`bn_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `capitaux`;

CREATE TABLE IF NOT EXISTS `capitaux` (
  `id_capitaux` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  `donor` varchar(255) NOT NULL,
  `datedone` date NOT NULL,
  `doneby` varchar(255) NOT NULL,
  `reste_amount` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_capitaux`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `capitaux_current`;

CREATE TABLE IF NOT EXISTS `capitaux_current` (
  `id_current_capitaux` int(11) NOT NULL AUTO_INCREMENT,
  `current_amount` int(11) NOT NULL,
  `date_current` date NOT NULL,
  PRIMARY KEY (`id_current_capitaux`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `capitaux_expense`;

CREATE TABLE IF NOT EXISTS `capitaux_expense` (
  `expeid` int(11) NOT NULL AUTO_INCREMENT,
  `expensename` varchar(255) NOT NULL,
  `Motif` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `datebill` date NOT NULL,
  `doneby` varchar(255) NOT NULL,
  PRIMARY KEY (`expeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `capitaux_histori_expense`;

CREATE TABLE IF NOT EXISTS `capitaux_histori_expense` (
  `id_cap_histo` int(11) NOT NULL AUTO_INCREMENT,
  `id_capitaux` int(11) NOT NULL,
  `id_expense` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id_cap_histo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cashiers`;

CREATE TABLE IF NOT EXISTS `cashiers` (
  `id_u` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codeR` int(11) NOT NULL,
  `dateaffectationcash` date NOT NULL,
  `createdtimeCash` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyCash` int(11) NOT NULL,
  PRIMARY KEY (`id_u`),
  KEY `codecashier` (`codecashier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `categopresta`;

CREATE TABLE IF NOT EXISTS `categopresta` (
  `id_categopresta` int(11) NOT NULL AUTO_INCREMENT,
  `nomcategopresta` varchar(255) CHARACTER SET utf8 NOT NULL,
  `namecategopresta` varchar(255) CHARACTER SET utf8 NOT NULL,
  `id_grade` int(11) DEFAULT NULL,
  `statucategopresta` int(11) NOT NULL,
  PRIMARY KEY (`id_categopresta`),
  KEY `id_grade` (`id_grade`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `categopresta_ins`;

CREATE TABLE IF NOT EXISTS `categopresta_ins` (
  `id_categopresta` int(11) NOT NULL AUTO_INCREMENT,
  `nomcategopresta` varchar(255) NOT NULL,
  `namecategopresta` varchar(255) NOT NULL,
  `id_grade` int(11) DEFAULT NULL,
  `satucategopresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_categopresta`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `catpresta`;

CREATE TABLE IF NOT EXISTS `catpresta` (
  `id_categopresta` int(11) NOT NULL AUTO_INCREMENT,
  `nomcategopresta` int(11) NOT NULL,
  `namecategopresta` int(11) NOT NULL,
  `id_grade` int(11) DEFAULT NULL,
  `satucategopresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_categopresta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cells`;

CREATE TABLE IF NOT EXISTS `cells` (
  `id_cell` int(11) NOT NULL AUTO_INCREMENT,
  `cell_code` varchar(100) NOT NULL,
  `nomcell` varchar(100) NOT NULL,
  `id_sector` int(11) NOT NULL,
  PRIMARY KEY (`id_cell`)
) ENGINE=MyISAM AUTO_INCREMENT=5071506 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `clinical_history`;

CREATE TABLE IF NOT EXISTS `clinical_history` (
  `cl_id` int(11) NOT NULL AUTO_INCREMENT,
  `history` text,
  `numero` varchar(225) DEFAULT NULL,
  `idHosp` int(11) DEFAULT NULL,
  `id_uM` int(11) DEFAULT NULL,
  `id_uInf` int(11) DEFAULT NULL,
  `hist_date` date DEFAULT NULL,
  `History_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `closed_stock`;

CREATE TABLE IF NOT EXISTS `closed_stock` (
  `sid` int(11) DEFAULT NULL,
  `closed_quantity` int(11) DEFAULT NULL,
  `closed_on` date DEFAULT NULL,
  `closed_by` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `closed_stock_counter`;

CREATE TABLE IF NOT EXISTS `closed_stock_counter` (
  `closeid` int(11) NOT NULL AUTO_INCREMENT,
  `closed_on` date DEFAULT NULL,
  `closed_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`closeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `consultations`;

CREATE TABLE IF NOT EXISTS `consultations` (
  `id_consu` int(11) NOT NULL AUTO_INCREMENT,
  `id_uR` int(11) DEFAULT NULL,
  `dateconsu` date NOT NULL,
  `heureconsu` varchar(255) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `anamnese` varchar(255) NOT NULL,
  `clihist` varchar(255) NOT NULL,
  `etatpatient` varchar(255) NOT NULL,
  `antecedent` varchar(255) NOT NULL,
  `allergie` varchar(255) NOT NULL,
  `examcli` varchar(255) NOT NULL,
  `signsymptomes` varchar(255) NOT NULL,
  `recommandations` varchar(255) NOT NULL,
  `poids` varchar(255) NOT NULL,
  `taille` varchar(11) NOT NULL,
  `temperature` varchar(255) NOT NULL,
  `tensionart` varchar(255) NOT NULL,
  `pouls` varchar(11) NOT NULL,
  `oxgen` varchar(255) DEFAULT NULL,
  `prediagnostic` varchar(255) NOT NULL,
  `postdiagnostic` varchar(255) NOT NULL,
  `recommandationnext` varchar(255) NOT NULL,
  `id_uM` int(11) DEFAULT NULL,
  `numero` varchar(255) NOT NULL,
  `id_typeconsult` int(11) DEFAULT NULL,
  `prixtypeconsult` decimal(11,2) NOT NULL,
  `prixtypeconsultCCO` decimal(11,2) NOT NULL,
  `autretypeconsult` varchar(255) NOT NULL,
  `prixautretypeconsult` decimal(11,2) NOT NULL,
  `prixautretypeconsultCCO` decimal(11,2) NOT NULL,
  `hospitalized` tinyint(4) DEFAULT NULL,
  `motifhospitalized` varchar(255) NOT NULL,
  `physio` tinyint(4) DEFAULT NULL,
  `motifphysio` varchar(255) NOT NULL,
  `transfer` tinyint(4) DEFAULT NULL,
  `motiftransfer` varchar(255) NOT NULL,
  `done` int(11) NOT NULL,
  `exhonereConsu` tinyint(1) NOT NULL,
  `id_assuConsu` int(11) NOT NULL,
  `assuranceConsuName` varchar(255) NOT NULL,
  `insupercent` int(11) NOT NULL,
  `id_factureConsult` int(11) DEFAULT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembou` decimal(11,2) NOT NULL,
  `motifrembou` varchar(255) NOT NULL,
  `modifie` tinyint(1) NOT NULL,
  `id_uM_modifie` int(11) DEFAULT NULL,
  `discountpercentConsu` int(11) DEFAULT NULL,
  `HiddenFile` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_consu`),
  KEY `numero` (`numero`),
  KEY `id_uM` (`id_uM`),
  KEY `id_factureConsult` (`id_factureConsult`)
) ENGINE=InnoDB AUTO_INCREMENT=2011 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `coordinateurs`;

CREATE TABLE IF NOT EXISTS `coordinateurs` (
  `id_u` int(11) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `dateaffectationcoordi` date NOT NULL,
  `createdtimeCoord` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyCoord` varchar(255) NOT NULL,
  `ceo` int(11) NOT NULL DEFAULT '0',
  KEY `id_u` (`id_u`),
  KEY `codecoordi` (`codecoordi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `countresults`;

CREATE TABLE IF NOT EXISTS `countresults` (
  `id_che` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) DEFAULT NULL,
  `id_consu` int(11) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `doneAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_che`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `diagnostic`;

CREATE TABLE IF NOT EXISTS `diagnostic` (
  `id_diagno` int(11) NOT NULL AUTO_INCREMENT,
  `nomdiagno` varchar(255) NOT NULL,
  `icd10` varchar(255) NOT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `groupe` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_diagno`)
) ENGINE=InnoDB AUTO_INCREMENT=2259 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `district`;

CREATE TABLE IF NOT EXISTS `district` (
  `id_district` int(11) NOT NULL,
  `district_code` varchar(100) NOT NULL,
  `nomdistrict` varchar(100) NOT NULL,
  `id_province` int(11) NOT NULL,
  PRIMARY KEY (`id_district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `doctorrecommandations`;

CREATE TABLE IF NOT EXISTS `doctorrecommandations` (
  `idreco` int(11) NOT NULL AUTO_INCREMENT,
  `recommandations` text,
  `idcategopresta` int(11) DEFAULT NULL,
  `idconsu` int(11) DEFAULT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `id_M` int(11) DEFAULT NULL,
  `id_inf` int(11) DEFAULT NULL,
  `duration` date DEFAULT NULL,
  `timet` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idreco`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `editedbillhisto`;

CREATE TABLE IF NOT EXISTS `editedbillhisto` (
  `eidbill` varchar(255) DEFAULT NULL,
  `id_factureHosp` varchar(255) DEFAULT NULL,
  `whoedit` int(11) DEFAULT NULL,
  `editedon` date DEFAULT NULL,
  `timee` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `billcatego` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `expenses`;

CREATE TABLE IF NOT EXISTS `expenses` (
  `expeid` int(11) NOT NULL AUTO_INCREMENT,
  `expensename` varchar(255) NOT NULL,
  `Motif` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `datebill` date NOT NULL,
  `codecashier` varchar(100) NOT NULL,
  PRIMARY KEY (`expeid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `grades`;

CREATE TABLE IF NOT EXISTS `grades` (
  `id_grade` int(11) NOT NULL AUTO_INCREMENT,
  `nomgrade` varchar(255) NOT NULL,
  `namegrade` varchar(255) NOT NULL,
  PRIMARY KEY (`id_grade`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `infirmiers`;

CREATE TABLE IF NOT EXISTS `infirmiers` (
  `id_u` int(11) NOT NULL,
  `codeinfirmier` varchar(255) NOT NULL,
  `inf_hosp` int(11) NOT NULL,
  `dateaffectationinf` date NOT NULL,
  `createdtimeInf` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyInf` int(11) NOT NULL,
  KEY `id_u` (`id_u`),
  KEY `codeinfirmier` (`codeinfirmier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `jourhosp_fact`;

CREATE TABLE IF NOT EXISTS `jourhosp_fact` (
  `id_jourHosp_fact` int(11) NOT NULL AUTO_INCREMENT,
  `numbill` varchar(255) NOT NULL,
  `id_hosp` int(11) NOT NULL,
  `id_resto` int(11) NOT NULL,
  `id_tour_de_salle` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `datedebut` date NOT NULL,
  `datefin` date NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  PRIMARY KEY (`id_jourHosp_fact`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `laborantins`;

CREATE TABLE IF NOT EXISTS `laborantins` (
  `id_u` int(11) NOT NULL,
  `codelabo` varchar(255) NOT NULL,
  `dateaffectationlabo` date NOT NULL,
  `createdtimeLabo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyLabo` int(11) NOT NULL,
  KEY `id_u` (`id_u`),
  KEY `codelabo` (`codelabo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `langues`;

CREATE TABLE IF NOT EXISTS `langues` (
  `id_langue` int(11) NOT NULL AUTO_INCREMENT,
  `francais` varchar(255) NOT NULL,
  `english` varchar(255) NOT NULL,
  PRIMARY KEY (`id_langue`)
) ENGINE=InnoDB AUTO_INCREMENT=300 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `medecins`;

CREATE TABLE IF NOT EXISTS `medecins` (
  `id_u` int(11) NOT NULL,
  `codemedecin` varchar(255) NOT NULL,
  `id_grade` int(11) DEFAULT NULL,
  `createdtimeMed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyMed` int(11) NOT NULL,
  KEY `codemedecin` (`codemedecin`),
  KEY `id_u` (`id_u`),
  KEY `id_grade` (`id_grade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_consom`;

CREATE TABLE IF NOT EXISTS `med_consom` (
  `id_medconsom` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestationConsom` int(11) NOT NULL,
  `prixprestationConsom` decimal(11,2) NOT NULL,
  `prixprestationConsomCCO` float(11,2) NOT NULL,
  `autreConsom` varchar(255) NOT NULL,
  `prixautreConsom` decimal(11,2) NOT NULL,
  `prixautreConsomCCO` float(11,2) NOT NULL,
  `qteConsom` int(11) NOT NULL,
  `id_uInfConsom` int(11) NOT NULL,
  `exhonereMedconsom` tinyint(1) NOT NULL,
  `id_assuConsom` int(11) NOT NULL,
  `insupercentConsom` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_consuConsom` int(11) NOT NULL,
  `id_factureMedConsom` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouConsom` decimal(11,2) NOT NULL,
  `discountpercentConsom` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medconsom`)
) ENGINE=InnoDB AUTO_INCREMENT=576 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_consom_hosp`;

CREATE TABLE IF NOT EXISTS `med_consom_hosp` (
  `id_medconsom` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestationConsom` int(11) DEFAULT NULL,
  `prixprestationConsom` decimal(11,2) NOT NULL,
  `prixprestationConsomCCO` decimal(11,2) NOT NULL,
  `autreConsom` varchar(255) NOT NULL,
  `prixautreConsom` float(11,2) NOT NULL,
  `prixautreConsomCCO` float(11,2) NOT NULL,
  `qteConsom` int(11) NOT NULL,
  `id_uInfConsom` int(11) NOT NULL,
  `exhonereMedconsom` tinyint(1) NOT NULL,
  `id_assuConsom` int(11) NOT NULL,
  `insupercentConsom` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospConsom` int(11) NOT NULL,
  `id_factureMedConsom` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouConsom` int(11) NOT NULL,
  PRIMARY KEY (`id_medconsom`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_consult`;

CREATE TABLE IF NOT EXISTS `med_consult` (
  `id_medconsu` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date DEFAULT NULL,
  `id_prestationConsu` int(11) DEFAULT NULL,
  `prixprestationConsu` decimal(11,2) DEFAULT NULL,
  `prixprestationConsuCCO` float(11,2) DEFAULT NULL,
  `autreConsu` varchar(255) DEFAULT NULL,
  `prixautreConsu` decimal(11,2) DEFAULT NULL,
  `prixautreConsuCCO` float(11,2) DEFAULT NULL,
  `exhonereMedconsu` tinyint(1) DEFAULT NULL,
  `id_uConsult` int(11) DEFAULT NULL,
  `id_assuServ` int(11) DEFAULT NULL,
  `insupercentServ` int(11) DEFAULT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `id_uM` int(11) DEFAULT NULL,
  `id_consuMed` int(11) DEFAULT NULL,
  `id_factureMedConsu` int(11) DEFAULT NULL,
  `codecashier` varchar(255) DEFAULT NULL,
  `codecoordi` varchar(255) DEFAULT NULL,
  `prixrembouConsu` decimal(11,2) DEFAULT NULL,
  `discountpercentConsult` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medconsu`),
  KEY `id_prestationConsu` (`id_prestationConsu`),
  KEY `id_uM` (`id_uM`),
  KEY `numero` (`numero`),
  KEY `id_consuMed` (`id_consuMed`)
) ENGINE=InnoDB AUTO_INCREMENT=599 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_consult_hosp`;

CREATE TABLE IF NOT EXISTS `med_consult_hosp` (
  `id_medconsu` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestationConsu` int(11) DEFAULT NULL,
  `prixprestationConsu` float(11,2) NOT NULL,
  `prixprestationConsuCCO` float(11,2) NOT NULL,
  `autreConsu` varchar(255) NOT NULL,
  `prixautreConsu` float(11,2) NOT NULL,
  `prixautreConsuCCO` float(11,2) NOT NULL,
  `qteConsu` int(11) NOT NULL DEFAULT '1',
  `exhonereMedconsu` tinyint(1) NOT NULL,
  `id_assuServ` int(11) NOT NULL,
  `insupercentServ` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospMed` int(11) NOT NULL,
  `id_factureMedConsu` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouConsu` int(11) NOT NULL,
  PRIMARY KEY (`id_medconsu`),
  KEY `id_prestationConsu` (`id_prestationConsu`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uI` (`id_uI`),
  KEY `numero` (`numero`),
  KEY `id_hospMed` (`id_hospMed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_inf`;

CREATE TABLE IF NOT EXISTS `med_inf` (
  `id_medinf` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestation` int(11) DEFAULT NULL,
  `prixprestation` decimal(11,2) NOT NULL,
  `prixprestationCCO` float(11,2) NOT NULL,
  `soinsfait` tinyint(1) NOT NULL,
  `datesoins` date NOT NULL,
  `autrePrestaM` varchar(255) NOT NULL,
  `prixautrePrestaM` decimal(11,2) NOT NULL,
  `prixautrePrestaMCCO` float(11,2) NOT NULL,
  `qteInf` int(11) NOT NULL DEFAULT '1',
  `id_uInfNurse` int(11) DEFAULT NULL,
  `exhonereMedinf` tinyint(1) NOT NULL,
  `autrePrestaI` varchar(255) NOT NULL,
  `id_assuInf` int(11) NOT NULL,
  `insupercentInf` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_consuInf` int(11) NOT NULL,
  `id_factureMedInf` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouInf` decimal(11,2) NOT NULL,
  `discountpercentInf` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medinf`),
  KEY `id_uM` (`id_uM`),
  KEY `id_prestation` (`id_prestation`),
  KEY `numero` (`numero`),
  KEY `id_uI` (`id_uI`),
  KEY `id_consuInf` (`id_consuInf`)
) ENGINE=InnoDB AUTO_INCREMENT=336 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_inf_hosp`;

CREATE TABLE IF NOT EXISTS `med_inf_hosp` (
  `id_medinf` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestation` int(11) DEFAULT NULL,
  `prixprestation` float(11,2) NOT NULL,
  `prixprestationCCO` float(11,2) NOT NULL,
  `soinsfait` tinyint(1) NOT NULL,
  `datesoins` date NOT NULL,
  `autrePrestaM` varchar(255) NOT NULL,
  `prixautrePrestaM` float(11,2) NOT NULL,
  `prixautrePrestaMCCO` float(11,2) NOT NULL,
  `qteInf` int(11) NOT NULL DEFAULT '1',
  `exhonereMedinf` tinyint(1) NOT NULL,
  `autrePrestaI` varchar(255) NOT NULL,
  `id_assuInf` int(11) NOT NULL,
  `insupercentInf` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospInf` int(11) NOT NULL,
  `id_factureMedInf` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouInf` int(11) NOT NULL,
  PRIMARY KEY (`id_medinf`),
  KEY `id_prestation` (`id_prestation`),
  KEY `numero` (`numero`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uI` (`id_uI`),
  KEY `id_hospInf` (`id_hospInf`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_kine`;

CREATE TABLE IF NOT EXISTS `med_kine` (
  `id_medkine` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestationKine` int(11) DEFAULT NULL,
  `prixprestationKine` decimal(11,2) NOT NULL,
  `prixprestationKineCCO` float(11,2) NOT NULL,
  `kinefait` tinyint(1) NOT NULL,
  `datekine` date NOT NULL,
  `autrePrestaK` varchar(255) NOT NULL,
  `prixautrePrestaK` decimal(11,2) NOT NULL,
  `prixautrePrestaKCCO` float(11,2) NOT NULL,
  `exhonereMedkine` tinyint(1) NOT NULL,
  `id_assuKine` int(11) NOT NULL,
  `insupercentKine` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uK` int(11) DEFAULT NULL,
  `id_consuKine` int(11) NOT NULL,
  `id_factureMedKine` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouKine` decimal(11,2) NOT NULL,
  `discountpercentkine` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medkine`),
  KEY `id_uM` (`id_uM`),
  KEY `id_prestationKine` (`id_prestationKine`),
  KEY `numero` (`numero`),
  KEY `id_uK` (`id_uK`),
  KEY `id_consuKine` (`id_consuKine`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_kine_hosp`;

CREATE TABLE IF NOT EXISTS `med_kine_hosp` (
  `id_medkine` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestationKine` int(11) DEFAULT NULL,
  `prixprestationKine` float(11,2) NOT NULL,
  `prixprestationKineCCO` float(11,2) NOT NULL,
  `kinefait` tinyint(1) NOT NULL,
  `datekine` date NOT NULL,
  `autrePrestaK` varchar(255) NOT NULL,
  `prixautrePrestaK` float(11,2) NOT NULL,
  `prixautrePrestaKCCO` float(11,2) NOT NULL,
  `qteKine` int(11) NOT NULL DEFAULT '1',
  `exhonereMedkine` tinyint(1) NOT NULL,
  `id_assuKine` int(11) NOT NULL,
  `insupercentKine` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uK` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospKine` int(11) NOT NULL,
  `id_factureMedKine` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouKine` int(11) NOT NULL,
  PRIMARY KEY (`id_medkine`),
  KEY `id_prestationKine` (`id_prestationKine`),
  KEY `numero` (`numero`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uK` (`id_uI`),
  KEY `id_hospKine` (`id_hospKine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_labo`;

CREATE TABLE IF NOT EXISTS `med_labo` (
  `id_medlabo` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestationExa` int(11) DEFAULT NULL,
  `prixprestationExa` decimal(11,2) NOT NULL,
  `prixprestationExaCCO` float(11,2) NOT NULL,
  `autreExamen` varchar(255) NOT NULL,
  `prixautreExamen` decimal(11,2) NOT NULL,
  `prixautreExamenCCO` float(11,2) NOT NULL,
  `exhonereMedlab` tinyint(1) NOT NULL,
  `id_assuLab` int(11) NOT NULL,
  `insupercentLab` int(11) NOT NULL,
  `examenfait` tinyint(1) NOT NULL,
  `resultats` varchar(255) NOT NULL,
  `valeurLab` varchar(255) NOT NULL,
  `minresultats` varchar(255) NOT NULL,
  `maxresultats` varchar(255) NOT NULL,
  `moreresultats` int(11) NOT NULL,
  `autreresultats` varchar(255) NOT NULL,
  `dateresultats` date NOT NULL,
  `diagnosticexa` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uL` int(11) DEFAULT NULL,
  `id_consuLabo` int(11) NOT NULL,
  `id_factureMedLabo` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouLabo` decimal(11,2) NOT NULL,
  `discountpercentLab` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medlabo`),
  KEY `id_prestationExa` (`id_prestationExa`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uL` (`id_uL`),
  KEY `numero` (`numero`),
  KEY `id_consuLabo` (`id_consuLabo`)
) ENGINE=InnoDB AUTO_INCREMENT=2607 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_labo_hosp`;

CREATE TABLE IF NOT EXISTS `med_labo_hosp` (
  `id_medlabo` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestationExa` int(11) DEFAULT NULL,
  `prixprestationExa` float(11,2) NOT NULL,
  `prixprestationExaCCO` float(11,2) NOT NULL,
  `autreExamen` varchar(255) NOT NULL,
  `prixautreExamen` float(11,2) NOT NULL,
  `prixautreExamenCCO` float(11,2) NOT NULL,
  `qteLab` int(11) NOT NULL DEFAULT '1',
  `exhonereMedlab` tinyint(1) NOT NULL,
  `id_assuLab` int(11) NOT NULL,
  `insupercentLab` int(11) NOT NULL,
  `examenfait` tinyint(1) NOT NULL,
  `resultats` varchar(255) NOT NULL,
  `valeurLab` varchar(255) NOT NULL,
  `minresultats` varchar(255) NOT NULL,
  `maxresultats` varchar(255) NOT NULL,
  `moreresultats` int(11) NOT NULL,
  `autreresultats` varchar(255) NOT NULL,
  `dateresultats` date NOT NULL,
  `diagnosticexa` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uL` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospLabo` int(11) NOT NULL,
  `id_factureMedLabo` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouLabo` int(11) NOT NULL,
  PRIMARY KEY (`id_medlabo`),
  KEY `id_prestationExa` (`id_prestationExa`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uI` (`id_uI`),
  KEY `id_uL` (`id_uL`),
  KEY `numero` (`numero`),
  KEY `id_hospLabo` (`id_hospLabo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_medoc`;

CREATE TABLE IF NOT EXISTS `med_medoc` (
  `id_medmedoc` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestationMedoc` int(11) NOT NULL,
  `prixprestationMedoc` decimal(11,2) NOT NULL,
  `prixprestationMedocCCO` float(11,2) NOT NULL,
  `autreMedoc` varchar(255) NOT NULL,
  `prixautreMedoc` decimal(11,2) NOT NULL,
  `prixautreMedocCCO` float(11,2) NOT NULL,
  `qteMedoc` int(11) NOT NULL DEFAULT '1',
  `id_uInfMedoc` int(11) NOT NULL,
  `exhonereMedmedoc` tinyint(1) NOT NULL,
  `id_assuMedoc` int(11) NOT NULL,
  `insupercentMedoc` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_consuMedoc` int(11) NOT NULL,
  `id_factureMedMedoc` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouMedoc` decimal(11,2) NOT NULL,
  `discountpercentMedoc` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medmedoc`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_medoc_hosp`;

CREATE TABLE IF NOT EXISTS `med_medoc_hosp` (
  `id_medmedoc` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestationMedoc` int(11) DEFAULT NULL,
  `prixprestationMedoc` float NOT NULL,
  `prixprestationMedocCCO` float(11,2) NOT NULL,
  `autreMedoc` varchar(255) NOT NULL,
  `prixautreMedoc` float(11,2) NOT NULL,
  `prixautreMedocCCO` float(11,2) NOT NULL,
  `qteMedoc` int(11) NOT NULL,
  `id_uInfMedoc` int(11) NOT NULL,
  `exhonereMedmedoc` tinyint(1) NOT NULL,
  `id_assuMedoc` int(11) NOT NULL,
  `insupercentMedoc` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospMedoc` int(11) NOT NULL,
  `id_factureMedMedoc` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouMedoc` int(11) NOT NULL,
  PRIMARY KEY (`id_medmedoc`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_motif`;

CREATE TABLE IF NOT EXISTS `med_motif` (
  `id_medmotif` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date DEFAULT NULL,
  `id_motif` int(11) DEFAULT NULL,
  `autremotif` varchar(255) DEFAULT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `id_uM` int(11) DEFAULT NULL,
  `id_consumotif` int(11) DEFAULT NULL,
  `dateupdatemotif` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_medmotif`),
  KEY `numero` (`numero`),
  KEY `id_uM` (`id_uM`),
  KEY `id_consumotif` (`id_consumotif`)
) ENGINE=MyISAM AUTO_INCREMENT=1526 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_ortho`;

CREATE TABLE IF NOT EXISTS `med_ortho` (
  `id_medortho` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestationOrtho` int(11) DEFAULT NULL,
  `prixprestationOrtho` decimal(11,2) NOT NULL,
  `prixprestationOrthoCCO` float(11,2) NOT NULL,
  `orthofait` tinyint(1) NOT NULL,
  `resultatsOrtho` varchar(255) NOT NULL,
  `dateortho` date NOT NULL,
  `autrePrestaO` varchar(255) NOT NULL,
  `prixautrePrestaO` decimal(11,2) NOT NULL,
  `prixautrePrestaOCCO` float(11,2) NOT NULL,
  `exhonereMedortho` tinyint(1) NOT NULL,
  `id_assuOrtho` int(11) NOT NULL,
  `insupercentOrtho` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uO` int(11) DEFAULT NULL,
  `id_consuOrtho` int(11) NOT NULL,
  `id_factureMedOrtho` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouOrtho` decimal(11,2) NOT NULL,
  `discountpercentOrtho` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medortho`),
  KEY `id_uM` (`id_uM`),
  KEY `id_prestationOrtho` (`id_prestationOrtho`),
  KEY `numero` (`numero`),
  KEY `id_uO` (`id_uO`),
  KEY `id_consuOrtho` (`id_consuOrtho`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_ortho_hosp`;

CREATE TABLE IF NOT EXISTS `med_ortho_hosp` (
  `id_medortho` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestationOrtho` int(11) DEFAULT NULL,
  `prixprestationOrtho` float(11,2) NOT NULL,
  `prixprestationOrthoCCO` float(11,2) NOT NULL,
  `orthofait` tinyint(1) NOT NULL,
  `dateortho` date NOT NULL,
  `autrePrestaO` varchar(255) NOT NULL,
  `prixautrePrestaO` float NOT NULL,
  `prixautrePrestaOCCO` float(11,2) NOT NULL,
  `qteOrtho` int(11) NOT NULL DEFAULT '1',
  `exhonereMedortho` tinyint(1) NOT NULL,
  `id_assuOrtho` int(11) NOT NULL,
  `insupercentOrtho` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uO` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospOrtho` int(11) NOT NULL,
  `id_factureMedOrtho` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouOrtho` int(11) NOT NULL,
  PRIMARY KEY (`id_medortho`),
  KEY `id_prestationOrtho` (`id_prestationOrtho`),
  KEY `numero` (`numero`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uO` (`id_uI`),
  KEY `id_hospOrtho` (`id_hospOrtho`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_psy`;

CREATE TABLE IF NOT EXISTS `med_psy` (
  `id_medpsy` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestation` int(11) DEFAULT NULL,
  `prixprestation` decimal(11,2) NOT NULL,
  `prixprestationCCO` float(11,2) NOT NULL,
  `spyfait` tinyint(1) NOT NULL,
  `datesoins` date NOT NULL,
  `autrePrestaM` varchar(255) NOT NULL,
  `prixautrePrestaM` decimal(11,2) NOT NULL,
  `prixautrePrestaMCCO` float(11,2) NOT NULL,
  `exhonereMedPsy` tinyint(1) NOT NULL,
  `autrePrestaI` varchar(255) NOT NULL,
  `id_assuPsy` int(11) NOT NULL,
  `insupercentPsy` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_consuPSy` int(11) NOT NULL,
  `id_factureMedPsy` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouPsy` decimal(11,2) NOT NULL,
  `discountpercentpys` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medpsy`),
  KEY `id_uM` (`id_uM`),
  KEY `id_prestation` (`id_prestation`),
  KEY `numero` (`numero`),
  KEY `id_uI` (`id_uI`),
  KEY `id_consuPSy` (`id_consuPSy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_radio`;

CREATE TABLE IF NOT EXISTS `med_radio` (
  `id_medradio` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestationRadio` int(11) DEFAULT NULL,
  `prixprestationRadio` decimal(11,2) NOT NULL,
  `prixprestationRadioCCO` float(11,2) NOT NULL,
  `autreRadio` varchar(255) NOT NULL,
  `prixautreRadio` decimal(11,2) NOT NULL,
  `prixautreRadioCCO` float(11,2) NOT NULL,
  `exhonereMedrad` tinyint(1) NOT NULL,
  `id_assuRad` int(11) NOT NULL,
  `insupercentRad` int(11) NOT NULL,
  `radiofait` tinyint(1) NOT NULL,
  `resultatsRad` varchar(255) NOT NULL,
  `dateradio` date NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uX` int(11) DEFAULT NULL,
  `id_consuRadio` int(11) NOT NULL,
  `id_factureMedRadio` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouRadio` decimal(11,2) NOT NULL,
  `discountpercentRadio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medradio`),
  KEY `id_prestationRadio` (`id_prestationRadio`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uX` (`id_uX`),
  KEY `numero` (`numero`),
  KEY `id_consuRadio` (`id_consuRadio`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_radio_hosp`;

CREATE TABLE IF NOT EXISTS `med_radio_hosp` (
  `id_medradio` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestationRadio` int(11) DEFAULT NULL,
  `prixprestationRadio` float(11,2) NOT NULL,
  `prixprestationRadioCCO` float(11,2) NOT NULL,
  `autreRadio` varchar(255) NOT NULL,
  `prixautreRadio` float(11,2) NOT NULL,
  `prixautreRadioCCO` float(11,2) NOT NULL,
  `qteRad` int(11) NOT NULL DEFAULT '1',
  `exhonereMedrad` tinyint(1) NOT NULL,
  `id_assuRad` int(11) NOT NULL,
  `insupercentRad` int(11) NOT NULL,
  `radiofait` tinyint(1) NOT NULL,
  `resultatsRad` varchar(255) NOT NULL,
  `dateradio` date NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uX` int(11) DEFAULT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospRadio` int(11) NOT NULL,
  `id_factureMedRadio` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouRadio` int(11) NOT NULL,
  PRIMARY KEY (`id_medradio`),
  KEY `id_prestationRadio` (`id_prestationRadio`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uX` (`id_uX`),
  KEY `id_uI` (`id_uI`),
  KEY `numero` (`numero`),
  KEY `id_hospRadio` (`id_hospRadio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_surge`;

CREATE TABLE IF NOT EXISTS `med_surge` (
  `id_medsurge` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_prestationSurge` int(11) DEFAULT NULL,
  `prixprestationSurge` decimal(11,2) NOT NULL,
  `prixprestationSurgeCCO` float(11,2) NOT NULL,
  `surgefait` tinyint(1) NOT NULL,
  `datesurge` date NOT NULL,
  `autrePrestaS` varchar(255) NOT NULL,
  `prixautrePrestaS` decimal(11,2) NOT NULL,
  `prixautrePrestaSCCO` float(11,2) NOT NULL,
  `exhonereMedsurge` tinyint(1) NOT NULL,
  `id_assuSurge` int(11) NOT NULL,
  `insupercentSurge` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uS` int(11) DEFAULT NULL,
  `id_consuSurge` int(11) NOT NULL,
  `id_factureMedSurge` int(11) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouSurge` decimal(11,2) NOT NULL,
  `discountpercentSurge` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_medsurge`),
  KEY `id_uM` (`id_uM`),
  KEY `id_prestationSurge` (`id_prestationSurge`),
  KEY `numero` (`numero`),
  KEY `id_uS` (`id_uS`),
  KEY `id_consuSurge` (`id_consuSurge`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `med_surge_hosp`;

CREATE TABLE IF NOT EXISTS `med_surge_hosp` (
  `id_medsurge` int(11) NOT NULL AUTO_INCREMENT,
  `datehosp` date NOT NULL,
  `id_prestationSurge` int(11) DEFAULT NULL,
  `prixprestationSurge` float(11,2) NOT NULL,
  `prixprestationSurgeCCO` float(11,2) NOT NULL,
  `surgefait` tinyint(1) NOT NULL,
  `autrePrestaS` varchar(255) NOT NULL,
  `prixautrePrestaS` float(11,2) NOT NULL,
  `prixautrePrestaSCCO` float(11,2) NOT NULL,
  `qteSurge` int(11) NOT NULL DEFAULT '1',
  `exhonereMedsurge` tinyint(1) NOT NULL,
  `id_assuSurge` int(11) NOT NULL,
  `insupercentSurge` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uI` int(11) DEFAULT NULL,
  `id_uS` int(11) DEFAULT NULL,
  `id_uCoor` int(11) DEFAULT NULL,
  `id_hospSurge` int(11) NOT NULL,
  `id_factureMedSurge` varchar(255) NOT NULL,
  `codecashier` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  `prixrembouSurge` int(11) NOT NULL,
  PRIMARY KEY (`id_medsurge`),
  KEY `id_prestationSurge` (`id_prestationSurge`),
  KEY `numero` (`numero`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uS` (`id_uI`),
  KEY `id_hospSurge` (`id_hospSurge`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `menageres`;

CREATE TABLE IF NOT EXISTS `menageres` (
  `id_u` int(11) NOT NULL,
  `codemenag` varchar(255) NOT NULL,
  `dateaffectationmenag` date NOT NULL,
  `createdtimeMenag` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyMenag` int(11) NOT NULL,
  PRIMARY KEY (`id_u`),
  KEY `codeaudit` (`codemenag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `messages`;

CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `receiverId` int(11) NOT NULL,
  `senderId` int(11) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `annexe` varchar(255) NOT NULL,
  `objet` varchar(255) NOT NULL,
  `datemessage` date NOT NULL,
  `realtime` time NOT NULL,
  `lu` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `more_med_labo`;

CREATE TABLE IF NOT EXISTS `more_med_labo` (
  `id_moremedlabo` int(11) NOT NULL AUTO_INCREMENT,
  `id_medlabo` int(11) NOT NULL,
  `id_prestationExa` int(11) DEFAULT NULL,
  `autreExamen` varchar(255) NOT NULL,
  `exhonereMedlab` tinyint(1) NOT NULL,
  `id_assuLab` int(11) NOT NULL,
  `resultats` varchar(255) NOT NULL,
  `autreresultats` varchar(255) NOT NULL,
  `valeurLab` varchar(255) NOT NULL,
  `minresultats` varchar(255) NOT NULL,
  `maxresultats` varchar(255) NOT NULL,
  `diagnosticexa` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uL` int(11) NOT NULL,
  PRIMARY KEY (`id_moremedlabo`),
  KEY `id_prestationExa` (`id_prestationExa`),
  KEY `id_uM` (`id_uM`),
  KEY `id_uL` (`id_uL`),
  KEY `numero` (`numero`),
  KEY `id_medlabo` (`id_medlabo`)
) ENGINE=InnoDB AUTO_INCREMENT=3432 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `motifs`;

CREATE TABLE IF NOT EXISTS `motifs` (
  `id_motif` int(11) NOT NULL AUTO_INCREMENT,
  `nommotif` varchar(255) NOT NULL,
  PRIMARY KEY (`id_motif`)
) ENGINE=MyISAM AUTO_INCREMENT=1476 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `nfssubexams`;

CREATE TABLE IF NOT EXISTS `nfssubexams` (
  `idCount` int(11) NOT NULL AUTO_INCREMENT,
  `id_prestation` int(11) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `rangesMen` text NOT NULL,
  `rangesWomen` varchar(100) DEFAULT NULL,
  `rangesChildren` varchar(100) DEFAULT NULL,
  `min` varchar(255) DEFAULT NULL,
  `max` varchar(255) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`idCount`)
) ENGINE=MyISAM AUTO_INCREMENT=1389 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `organisations`;

CREATE TABLE IF NOT EXISTS `organisations` (
  `id_org` int(11) NOT NULL AUTO_INCREMENT,
  `nomOrg` varchar(255) NOT NULL,
  `lieuOrg` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_org`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `orthopedistes`;

CREATE TABLE IF NOT EXISTS `orthopedistes` (
  `id_u` int(11) NOT NULL,
  `codeortho` varchar(255) NOT NULL,
  `dateaffectationortho` date NOT NULL,
  `createdtimeOrt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyOrt` int(11) NOT NULL,
  PRIMARY KEY (`id_u`),
  KEY `codeortho` (`codeortho`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `patients`;

CREATE TABLE IF NOT EXISTS `patients` (
  `id_u` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `reference_id` varchar(255) NOT NULL,
  `anneeadhesion` date NOT NULL,
  `profession` varchar(255) NOT NULL,
  `anneeNaiss` int(11) NOT NULL,
  `moisNaiss` int(11) NOT NULL,
  `jourNaiss` int(11) NOT NULL,
  `date_naissance` date NOT NULL,
  `poidsPa` varchar(255) NOT NULL,
  `taillePa` varchar(11) NOT NULL,
  `temperaturePa` varchar(255) NOT NULL,
  `tensionarteriellePa` varchar(255) NOT NULL,
  `poulsPa` varchar(11) NOT NULL,
  `oxgen` varchar(255) NOT NULL,
  `id_org` int(11) DEFAULT NULL,
  `bill` int(11) NOT NULL,
  `id_assurance` int(11) DEFAULT NULL,
  `carteassuranceid` varchar(255) NOT NULL,
  `numeropolice` varchar(255) NOT NULL,
  `adherent` varchar(255) NOT NULL,
  `createdtimePa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyPa` int(11) DEFAULT NULL,
  KEY `numero` (`numero`),
  KEY `id_u` (`id_u`),
  KEY `id_assurance` (`id_assurance`),
  KEY `createdbyPa` (`createdbyPa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `patients_hosp`;

CREATE TABLE IF NOT EXISTS `patients_hosp` (
  `id_hosp` int(11) NOT NULL AUTO_INCREMENT,
  `id_uHosp` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `reference_idHosp` varchar(255) NOT NULL,
  `numroomPa` varchar(255) NOT NULL,
  `numlitPa` varchar(11) NOT NULL,
  `dateEntree` date NOT NULL,
  `heureEntree` varchar(255) NOT NULL,
  `prixroom` decimal(11,2) NOT NULL,
  `prixroomCCO` decimal(11,2) NOT NULL,
  `dateSortie` date NOT NULL,
  `heureSortie` varchar(255) NOT NULL,
  `statusPaHosp` int(11) NOT NULL,
  `poidsPa` varchar(255) NOT NULL,
  `taillePa` varchar(11) NOT NULL,
  `temperaturePa` varchar(255) NOT NULL,
  `tensionarteriellePa` varchar(255) NOT NULL,
  `poulsPa` varchar(11) NOT NULL,
  `idorgBillHosp` int(11) DEFAULT NULL,
  `insupercent_hosp` int(11) NOT NULL,
  `id_assuHosp` int(11) NOT NULL,
  `nomassuranceHosp` varchar(255) NOT NULL,
  `idcardbillHosp` varchar(255) NOT NULL,
  `numpolicebillHosp` varchar(255) NOT NULL,
  `adherentbillHosp` varchar(255) NOT NULL,
  `id_factureHosp` varchar(255) DEFAULT NULL,
  `statusBill` tinyint(1) NOT NULL,
  `codeaccount` varchar(255) NOT NULL,
  `id_consuHosp` int(11) DEFAULT NULL,
  `codecashierHosp` varchar(255) NOT NULL,
  `codecoordiHosp` varchar(255) NOT NULL,
  `vouchernumHosp` varchar(255) NOT NULL,
  `createdtimePa` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyPa` int(11) DEFAULT NULL,
  `updatedTime` datetime NOT NULL,
  `updateBy` int(11) NOT NULL,
  PRIMARY KEY (`id_hosp`),
  KEY `numero` (`numero`),
  KEY `id_assurance` (`id_assuHosp`),
  KEY `createdbyPa` (`createdbyPa`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prepostdia`;

CREATE TABLE IF NOT EXISTS `prepostdia` (
  `id_dia` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_predia` int(11) DEFAULT NULL,
  `autrepredia` varchar(255) NOT NULL,
  `id_postdia` int(11) DEFAULT NULL,
  `autrepostdia` varchar(255) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_consudia` int(11) NOT NULL,
  `dateupdatedia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dia`),
  KEY `id_consudia` (`id_consudia`),
  KEY `id_uM` (`id_uM`),
  KEY `numero` (`numero`)
) ENGINE=InnoDB AUTO_INCREMENT=2140 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prepostdia_plus`;

CREATE TABLE IF NOT EXISTS `prepostdia_plus` (
  `id_dia` int(11) NOT NULL AUTO_INCREMENT,
  `dateconsu` date NOT NULL,
  `id_predia` int(11) DEFAULT NULL,
  `autrepredia` varchar(255) DEFAULT NULL,
  `id_postdia` int(11) DEFAULT NULL,
  `autrepostdia` varchar(255) DEFAULT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_consudia` int(11) NOT NULL,
  `dateupdatedia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_assuref`;

CREATE TABLE IF NOT EXISTS `prestations_assuref` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1261 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_bk`;

CREATE TABLE IF NOT EXISTS `prestations_bk` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1139 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_britam`;

CREATE TABLE IF NOT EXISTS `prestations_britam` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1378 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_cogebanque`;

CREATE TABLE IF NOT EXISTS `prestations_cogebanque` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1370 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_equity`;

CREATE TABLE IF NOT EXISTS `prestations_equity` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1953 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_farge`;

CREATE TABLE IF NOT EXISTS `prestations_farge` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1135 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_hope_and_home_for_children`;

CREATE TABLE IF NOT EXISTS `prestations_hope_and_home_for_children` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1882 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_hpg`;

CREATE TABLE IF NOT EXISTS `prestations_hpg` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1944 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_itm`;

CREATE TABLE IF NOT EXISTS `prestations_itm` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '0.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=MyISAM AUTO_INCREMENT=395 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_mmi`;

CREATE TABLE IF NOT EXISTS `prestations_mmi` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1136 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_musa`;

CREATE TABLE IF NOT EXISTS `prestations_musa` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1138 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_private`;

CREATE TABLE IF NOT EXISTS `prestations_private` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=2107 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_radiant`;

CREATE TABLE IF NOT EXISTS `prestations_radiant` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1307 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_rssb`;

CREATE TABLE IF NOT EXISTS `prestations_rssb` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1907 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_saham`;

CREATE TABLE IF NOT EXISTS `prestations_saham` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1219 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_sanlam`;

CREATE TABLE IF NOT EXISTS `prestations_sanlam` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1492 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_soras`;

CREATE TABLE IF NOT EXISTS `prestations_soras` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1446 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_uap`;

CREATE TABLE IF NOT EXISTS `prestations_uap` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1355 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_ur`;

CREATE TABLE IF NOT EXISTS `prestations_ur` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1139 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `prestations_urwego`;

CREATE TABLE IF NOT EXISTS `prestations_urwego` (
  `id_prestation` int(11) NOT NULL AUTO_INCREMENT,
  `nompresta` varchar(255) NOT NULL,
  `namepresta` varchar(255) NOT NULL,
  `prixpresta` decimal(11,2) NOT NULL DEFAULT '0.00',
  `prixprestaCCO` decimal(11,2) NOT NULL DEFAULT '-1.00',
  `id_categopresta` int(11) NOT NULL,
  `id_souscategopresta` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `statupresta` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prestation`)
) ENGINE=InnoDB AUTO_INCREMENT=1135 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `products`;

CREATE TABLE IF NOT EXISTS `products` (
  `pro_id` int(11) NOT NULL AUTO_INCREMENT,
  `productname` text,
  `mesure` varchar(255) DEFAULT NULL,
  `id_categopresta` int(11) DEFAULT '22',
  PRIMARY KEY (`pro_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product_category`;

CREATE TABLE IF NOT EXISTS `product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `names` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `province`;

CREATE TABLE IF NOT EXISTS `province` (
  `id_province` int(11) NOT NULL AUTO_INCREMENT,
  `nomprovince` varchar(255) NOT NULL,
  `nameprovince` varchar(255) NOT NULL,
  PRIMARY KEY (`id_province`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `radiologues`;

CREATE TABLE IF NOT EXISTS `radiologues` (
  `id_u` int(11) NOT NULL,
  `coderadio` varchar(255) NOT NULL,
  `dateaffectationradio` date NOT NULL,
  `createdtimeRadio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyRadio` int(11) NOT NULL,
  KEY `id_u` (`id_u`),
  KEY `coderadio` (`coderadio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `receptionistes`;

CREATE TABLE IF NOT EXISTS `receptionistes` (
  `id_u` int(11) NOT NULL,
  `codereceptio` varchar(255) NOT NULL,
  `codeC` int(11) NOT NULL,
  `dateaffectationreceptio` date NOT NULL,
  `createdtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbyRec` int(11) NOT NULL,
  KEY `id_u` (`id_u`),
  KEY `codereceptio` (`codereceptio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rendez_vous`;

CREATE TABLE IF NOT EXISTS `rendez_vous` (
  `id_rdv` int(11) NOT NULL AUTO_INCREMENT,
  `dateattribution` date NOT NULL,
  `daterdv` date NOT NULL,
  `heurerdv` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `autrePa` varchar(255) DEFAULT NULL,
  `autreTel` varchar(255) DEFAULT NULL,
  `motifrdv` varchar(255) NOT NULL,
  `id_consurdv` int(11) DEFAULT NULL,
  `doneby` int(11) DEFAULT NULL,
  `statusRdv` int(11) NOT NULL,
  PRIMARY KEY (`id_rdv`),
  KEY `id_uM` (`id_uM`),
  KEY `numero` (`numero`),
  KEY `doneby` (`doneby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `requisition`;

CREATE TABLE IF NOT EXISTS `requisition` (
  `re_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `quantity` int(11) DEFAULT NULL,
  `mesure` varchar(255) DEFAULT NULL,
  `asked_by` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `approvedby` int(11) DEFAULT NULL,
  `approved_on` date DEFAULT NULL,
  `comment` text,
  `approve_specialRequi` int(11) DEFAULT NULL,
  `approved_onRequi` date DEFAULT NULL,
  `stockout_status` int(11) NOT NULL DEFAULT '0',
  `product_catego` int(11) DEFAULT NULL,
  PRIMARY KEY (`re_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rn_table`;

CREATE TABLE IF NOT EXISTS `rn_table` (
  `rn_type` varchar(255) NOT NULL,
  `rn_id_l` varchar(255) NOT NULL,
  `rn_id_n` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `rooms`;

CREATE TABLE IF NOT EXISTS `rooms` (
  `id_room` int(11) NOT NULL AUTO_INCREMENT,
  `numroom` varchar(255) NOT NULL,
  `typeroom` int(11) NOT NULL,
  `statusA` varchar(255) NOT NULL,
  `statusB` varchar(255) DEFAULT NULL,
  `statusC` varchar(255) DEFAULT NULL,
  `statusD` varchar(255) DEFAULT NULL,
  `statusE` varchar(255) DEFAULT NULL,
  `statusF` varchar(255) DEFAULT NULL,
  `statusG` varchar(255) DEFAULT NULL,
  `statusH` varchar(255) DEFAULT NULL,
  `statusI` varchar(255) DEFAULT NULL,
  `statusJ` varchar(255) DEFAULT NULL,
  `statusK` varchar(255) DEFAULT NULL,
  `statusL` varchar(255) DEFAULT NULL,
  `statusM` varchar(255) DEFAULT NULL,
  `statusN` varchar(255) DEFAULT NULL,
  `toilet` int(11) NOT NULL,
  `id_prestationHosp` int(11) NOT NULL,
  PRIMARY KEY (`id_room`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sectors`;

CREATE TABLE IF NOT EXISTS `sectors` (
  `id_sector` int(11) NOT NULL AUTO_INCREMENT,
  `nomsector` varchar(100) NOT NULL,
  `id_district` int(11) NOT NULL,
  PRIMARY KEY (`id_sector`)
) ENGINE=InnoDB AUTO_INCREMENT=11417 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `servicemed`;

CREATE TABLE IF NOT EXISTS `servicemed` (
  `id_servicemed` int(11) NOT NULL AUTO_INCREMENT,
  `dateaffectationmed` date DEFAULT NULL,
  `codemedecin` varchar(255) DEFAULT NULL,
  `id_categopresta` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_servicemed`),
  KEY `codemedecin` (`codemedecin`),
  KEY `id_categopresta` (`id_categopresta`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `services`;

CREATE TABLE IF NOT EXISTS `services` (
  `id_service` int(11) NOT NULL AUTO_INCREMENT,
  `nomservice` varchar(255) NOT NULL,
  `id_grade` int(11) NOT NULL,
  PRIMARY KEY (`id_service`),
  KEY `id_grade` (`id_grade`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sms_sent`;

CREATE TABLE IF NOT EXISTS `sms_sent` (
  `id_che` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(255) DEFAULT NULL,
  `id_consu` int(11) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_che`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sn_table`;

CREATE TABLE IF NOT EXISTS `sn_table` (
  `sn_type` varchar(255) NOT NULL,
  `sn_id_y` int(11) NOT NULL,
  `sn_id_l` varchar(255) NOT NULL,
  `sn_id_n` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `souscategopresta`;

CREATE TABLE IF NOT EXISTS `souscategopresta` (
  `id_souscatego` int(11) NOT NULL AUTO_INCREMENT,
  `nomsouscatego` varchar(255) NOT NULL,
  `catego_id` int(11) NOT NULL,
  `souscatego_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_souscatego`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `spermo_med_labo`;

CREATE TABLE IF NOT EXISTS `spermo_med_labo` (
  `id_spermomedlabo` int(11) NOT NULL AUTO_INCREMENT,
  `id_medlabo` int(11) NOT NULL,
  `volume` varchar(255) NOT NULL,
  `densite` varchar(255) NOT NULL,
  `viscosite` varchar(255) NOT NULL,
  `ph` varchar(255) NOT NULL,
  `aspect` varchar(255) NOT NULL,
  `examdirect` varchar(255) NOT NULL,
  `zeroheureafter` varchar(255) NOT NULL,
  `uneheureafter` varchar(255) NOT NULL,
  `deuxheureafter` varchar(255) NOT NULL,
  `troisheureafter` varchar(255) NOT NULL,
  `quatreheureafter` varchar(255) NOT NULL,
  `numeration` varchar(255) NOT NULL,
  `vn` varchar(255) NOT NULL,
  `formtypik` varchar(255) NOT NULL,
  `formatypik` varchar(255) NOT NULL,
  `autre` varchar(255) NOT NULL,
  `conclusion` varchar(255) NOT NULL,
  `id_assuLab` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `id_uL` int(11) NOT NULL,
  PRIMARY KEY (`id_spermomedlabo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `stockin`;

CREATE TABLE IF NOT EXISTS `stockin` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  `mesure` varchar(255) DEFAULT NULL,
  `stokin` date DEFAULT NULL,
  `tme` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `manufacturedate` date DEFAULT NULL,
  `expireddate` date DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `suppliername` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `addby` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '22',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stockin_history`;

CREATE TABLE IF NOT EXISTS `stockin_history` (
  `s_hid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL,
  `quantityIn` int(11) DEFAULT NULL,
  `mesure` int(11) DEFAULT NULL,
  `doneon` date DEFAULT NULL,
  `doneby` int(11) DEFAULT NULL,
  `stockouttime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '22',
  PRIMARY KEY (`s_hid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stockkeeper`;

CREATE TABLE IF NOT EXISTS `stockkeeper` (
  `id_u` int(11) NOT NULL,
  `codestock` varchar(11) DEFAULT NULL,
  `dateaffectionstock` date DEFAULT NULL,
  `createdtimestock` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdbystock` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_u`),
  KEY `codestock` (`codestock`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `stockout_history`;

CREATE TABLE IF NOT EXISTS `stockout_history` (
  `s_hid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL,
  `existing_qty` int(11) DEFAULT NULL,
  `quantityOut` int(11) DEFAULT NULL,
  `mesure` int(11) DEFAULT NULL,
  `doneon` date DEFAULT NULL,
  `takenby` varchar(255) DEFAULT NULL,
  `doneby` int(11) DEFAULT NULL,
  `stockouttime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '22',
  PRIMARY KEY (`s_hid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `temp_facture`;

CREATE TABLE IF NOT EXISTS `temp_facture` (
  `id_tempfacture` int(11) NOT NULL AUTO_INCREMENT,
  `autreprestaBill` varchar(255) NOT NULL,
  `prixunitaire` int(11) NOT NULL,
  `quantitytempfact` int(11) NOT NULL,
  `totaltempfact` int(11) NOT NULL,
  `date_tempfacture` date NOT NULL,
  `numero` varchar(255) NOT NULL,
  `codecoordi` varchar(255) NOT NULL,
  PRIMARY KEY (`id_tempfacture`),
  KEY `codecoordi` (`codecoordi`),
  KEY `numero` (`numero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `treatment_plus`;

CREATE TABLE IF NOT EXISTS `treatment_plus` (
  `id_treatment` int(11) NOT NULL AUTO_INCREMENT,
  `treatments` varchar(255) NOT NULL,
  `id_consu` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `id_uM` int(11) NOT NULL,
  `date_treatment` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_treatment`),
  KEY `id_consu` (`id_consu`),
  KEY `numero` (`numero`),
  KEY `id_uM` (`id_uM`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `utilisateurs`;

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_u` int(11) NOT NULL AUTO_INCREMENT,
  `nom_u` varchar(255) NOT NULL,
  `prenom_u` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `sexe` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `autreadresse` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `e_mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `datamanager` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `district` varchar(255) NOT NULL,
  `secteur` varchar(255) NOT NULL,
  `cell` varchar(255) DEFAULT NULL,
  `village` varchar(255) DEFAULT NULL,
  `updatedtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updatedby` int(11) NOT NULL,
  PRIMARY KEY (`id_u`)
) ENGINE=InnoDB AUTO_INCREMENT=1510 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `valeurs_lab`;

CREATE TABLE IF NOT EXISTS `valeurs_lab` (
  `id_valeur` int(11) NOT NULL AUTO_INCREMENT,
  `valeur` varchar(255) DEFAULT NULL,
  `min_valeur` varchar(255) NOT NULL,
  `max_valeur` varchar(255) NOT NULL,
  `id_examen` int(11) NOT NULL,
  `nomexam` varchar(255) NOT NULL,
  PRIMARY KEY (`id_valeur`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `villages`;

CREATE TABLE IF NOT EXISTS `villages` (
  `id_village` int(11) NOT NULL AUTO_INCREMENT,
  `village_code` varchar(100) NOT NULL,
  `nomvillage` varchar(100) NOT NULL,
  `id_cell` int(11) NOT NULL,
  PRIMARY KEY (`id_village`)
) ENGINE=MyISAM AUTO_INCREMENT=14880 DEFAULT CHARSET=latin1;

ALTER TABLE `cashiers`
  ADD CONSTRAINT `cashiers_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `utilisateurs` (`id_u`);

ALTER TABLE `categopresta`
  ADD CONSTRAINT `categopresta_ibfk_1` FOREIGN KEY (`id_grade`) REFERENCES `grades` (`id_grade`) ON UPDATE CASCADE;

ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_13` FOREIGN KEY (`id_factureConsult`) REFERENCES `bills` (`id_bill`) ON UPDATE CASCADE;

ALTER TABLE `coordinateurs`
  ADD CONSTRAINT `coordinateurs_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `utilisateurs` (`id_u`);

ALTER TABLE `infirmiers`
  ADD CONSTRAINT `infirmiers_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `utilisateurs` (`id_u`);

ALTER TABLE `laborantins`
  ADD CONSTRAINT `laborantins_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `utilisateurs` (`id_u`);

ALTER TABLE `servicemed`
  ADD CONSTRAINT `servicemed_ibfk_3` FOREIGN KEY (`id_categopresta`) REFERENCES `categopresta` (`id_categopresta`) ON UPDATE CASCADE;

ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`id_grade`) REFERENCES `grades` (`id_grade`) ON UPDATE CASCADE;

