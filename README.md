
# Installation sur un Serveur Linux

Ce guide vous explique comment réaliser l'installation et la configuration sur un serveur Linux.

## 1 - Suppression des Logs

```bash
cd ~/www/susar_eu_v2/var/log/
sudo rm dev.log traceDev.log trace_import_CTLL.log
```

## 2 - modification des droits sur les répertoires

```bash
cd ~/www/susar_eu_v2/
sudo chown -R apache:apache ./public ./var ./temp
sudo chmod -R 755 ./public ./var ./temp
```

## 3 - modification des info de BDD

```bash
vim .env.local
```

