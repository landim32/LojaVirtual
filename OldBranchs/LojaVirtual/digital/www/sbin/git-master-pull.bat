@echo off
git checkout dev
git merge -s ours master
git checkout master
git merge dev
git sync
